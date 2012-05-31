package net.triptech.tripdocs.agents.monitor;

import java.io.IOException;
import java.nio.file.Path;
import java.nio.file.Paths;

import org.apache.log4j.Logger;

/**
 * Thread to process watch events.
 */
public class WatchThread extends Thread {

    /** The Constant logger. */
    private final static Logger logger = Logger.getLogger(WatchThread.class);

    /** The dir. */
    private final Path dir;

    /** The recursive. */
    private final boolean recursive;


    /**
     * Instantiates a new watch thread.
     *
     * @param dir the dir
     * @param recursive the recursive
     */
    public WatchThread(final String directory, final boolean recursive) {
        this.dir = Paths.get(directory);
        this.recursive = recursive;
    }

    /* (non-Javadoc)
     * @see java.lang.Thread#run()
     */
    @Override
    public void run() {
        try {
            new WatchDirectory(this.dir, this.recursive).processEvents();
        }
        catch (final IOException ie) {
            logger.error("I/O exception when watching directory: " + ie.getMessage());
        }
    }
}
