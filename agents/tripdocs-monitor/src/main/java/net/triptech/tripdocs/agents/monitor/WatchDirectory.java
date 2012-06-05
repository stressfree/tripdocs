package net.triptech.tripdocs.agents.monitor;

import static java.nio.file.LinkOption.NOFOLLOW_LINKS;

import java.io.IOException;

import java.nio.file.FileSystems;
import java.nio.file.FileVisitResult;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.SimpleFileVisitor;
import java.nio.file.StandardWatchEventKinds;
import java.nio.file.WatchEvent;
import java.nio.file.WatchKey;
import java.nio.file.WatchService;
import java.nio.file.attribute.BasicFileAttributes;

import java.util.Formatter;
import java.util.HashMap;
import java.util.Map;
import java.util.Map.Entry;

import org.apache.log4j.Logger;

/**
 * Watch a directory (or tree) for changes to files.
 * Based on: http://docs.oracle.com/javase/tutorial/essential/io/examples/WatchDir.java
 *
 */
public class WatchDirectory {

    /** The Constant logger. */
    private final static Logger logger = Logger.getLogger(WatchDirectory.class);

    /** The watcher. */
    private final WatchService watcher;

    /** The keys. */
    private final Map<WatchKey, Path> keys;

    /** The aws access key. */
    final String awsAccessKey;

    /** The aws secret key. */
    final String awsSecretKey;

    /** The s3 bucket. */
    final String s3Bucket;

    /** The trace. */
    private boolean trace = true;

    /**
     * Cast.
     *
     * @param <T> the generic type
     * @param event the event
     * @return the watch event
     */
    @SuppressWarnings("unchecked")
    static <T> WatchEvent<T> cast(final WatchEvent<?> event) {
        return (WatchEvent<T>) event;
    }

    /**
     * Register the given directory with the WatchService.
     *
     * @param dir the dir
     * @throws IOException Signals that an I/O exception has occurred.
     */
    void register(final Path dir) throws IOException {
        final WatchKey key = dir.register(this.watcher,
                StandardWatchEventKinds.ENTRY_CREATE,
                StandardWatchEventKinds.ENTRY_DELETE,
                StandardWatchEventKinds.ENTRY_MODIFY);
        if (this.trace) {
            final Path prev = this.keys.get(key);
            if (prev == null) {            	;
                logger.info(new Formatter().format("Registering: %s", dir).out());
            } else {
                if (!dir.equals(prev)) {
                    logger.debug(new Formatter().format(
                            "Updating: %s -> %s", prev, dir).out());
                }
            }
        }
        this.keys.put(key, dir);
    }

    /**
     * Register the given directory, and all its sub-directories, with the
     * WatchService.
     *
     * @param start the start
     * @throws IOException Signals that an I/O exception has occurred.
     */
    private void registerAll(final Path start) throws IOException {
        // register directory and sub-directories
        Files.walkFileTree(start, new SimpleFileVisitor<Path>() {
            @Override
            public FileVisitResult preVisitDirectory(final Path dir,
                    final BasicFileAttributes attrs) throws IOException {
                logger.info(new Formatter().format("Monitoring %s ...", dir).out());
                WatchDirectory.this.register(dir);
                return FileVisitResult.CONTINUE;
            }
        });
    }

    /**
     * Creates a WatchService and registers the given directory.
     *
     * @param dir the dir
     * @param awsAccessKeyVal the aws access key val
     * @param awsSecretKeyVal the aws secret val
     * @param s3BucketVal the s3 bucket val
     * @throws IOException Signals that an I/O exception has occurred.
     */
    WatchDirectory(final Path dir, final String awsAccessKeyVal,
            final String awsSecretKeyVal, final String s3BucketVal) throws IOException {
        this.watcher = FileSystems.getDefault().newWatchService();
        this.keys = new HashMap<WatchKey, Path>();
        this.awsAccessKey = awsAccessKeyVal;
        this.awsSecretKey = awsSecretKeyVal;
        this.s3Bucket = s3BucketVal;

        logger.info(new Formatter().format("Scanning %s ...", dir).out());
        this.registerAll(dir);
        logger.info("All directories registered");

        // enable trace after initial registration
        this.trace = true;
    }

    /**
     * Process all events for keys queued to the watcher.
     */
    void processEvents() {
         Path dir = null;
         for (;;) {
             // wait for key to be signaled
             final WatchKey key;
             try {
                 key = this.watcher.take();
             }
             catch (final InterruptedException x) {
                 logger.error("Watcher thread interrupted: " + x.getMessage());
                 return;
             }

             dir = this.keys.get(key);
             if (dir == null) {
                 logger.error("WatchKey not recognized!!");
                 continue;
             }

             for (final WatchEvent<?> event : key.pollEvents()) {
                 final WatchEvent.Kind<?> kind = event.kind();

                 // TBD - provide example of how OVERFLOW event is handled
                 if (kind == StandardWatchEventKinds.OVERFLOW) {
                     continue;
                 }

                 // Context for directory entry event is the file name of entry
                 final WatchEvent<Path> ev = WatchDirectory.cast(event);
                 final Path name  = ev.context();
                 final Path child = dir.resolve(name);

                 logger.info(new Formatter().format(
                         "%s: %s", event.kind().name(), child).out());

                 if (kind == StandardWatchEventKinds.ENTRY_CREATE
                         || kind == StandardWatchEventKinds.ENTRY_MODIFY) {
                     try {
                         BasicFileAttributes attr = Files.readAttributes(
                                 child, BasicFileAttributes.class);

                         if (attr.isDirectory()) {
                             // If a directory is created, and watching recursively, then
                             // register it and its sub-directories.
                             this.registerAll(child);
                         }

                         if (attr.isRegularFile()) {
                             // If a regular file upload it to S3.
                             String url = S3Uploader.upload(child, s3Bucket,
                                     awsAccessKey, awsSecretKey);

                             logger.info("File uploaded to: " + url);
                         }
                     } catch (IOException x) {
                         logger.error("Error reading path attributes: " + x.getMessage());
                     } catch (S3UploadException s3e) {
                         logger.error("Error uploading to S3: " + s3e.getMessage());
                     }
                 }

                 // if event is DELETE ...
                 if (kind == StandardWatchEventKinds.ENTRY_DELETE) {
                     WatchKey ckey = null;
                     for (final Entry<WatchKey, Path> e : this.keys.entrySet()) {
                         if (e.getValue().equals(child)) {
                             ckey = e.getKey();
                             break;
                         }
                     }
                     // ... and child is a directory and it is effectively deleted.
                     if ((ckey != null) && Files.notExists(child, NOFOLLOW_LINKS)) {
                         logger.info(new Formatter().format(
                                 "%s does not exist... ", child).out());
                         if (ckey.isValid()) {
                             logger.info("... but key is valid.");
                         } else {
                             logger.info("... and key is cancelled.");
                         }
                     }
                 }
             }

             // reset key and remove from set if directory no longer accessible
             final boolean valid = key.reset();
             if (!valid) {
                 logger.info(new Formatter().format("Removing watch key for %s.", dir));
                 this.keys.remove(key);

                 // all directories are inaccessible
                 if (this.keys.isEmpty()) {
                     break;
                 }
             }
         }
     }
}
