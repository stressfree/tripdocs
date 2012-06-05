package net.triptech.tripdocs.agents.monitor;

import java.io.IOException;
import java.nio.file.Path;
import java.nio.file.Paths;

import org.apache.commons.lang.StringUtils;
import org.apache.log4j.Logger;

/**
 * Thread to process watch events.
 */
public class WatchThread extends Thread {

    /** The Constant logger. */
    private final static Logger logger = Logger.getLogger(WatchThread.class);

    /** The dir. */
    private final Path dir;

    /** The aws access key. */
    private final String awsAccessKey;

    /** The aws secret key. */
    private final String awsSecretKey;

    /** The s3 bucket. */
    private final String s3Bucket;


    /**
     * Instantiates a new watch thread.
     *
     * @param directory the directory
     * @param awsAccessKeyVal the aws access key val
     * @param awsSecretKeyVal the aws secret val
     * @param s3BucketVal the s3 bucket val
     */
    public WatchThread(final String directory, final String awsAccessKeyVal,
            final String awsSecretKeyVal, final String s3BucketVal) {

        if (StringUtils.isBlank(directory)) {
            throw new IllegalArgumentException("A directory to monitor is required");
        }

        if (StringUtils.isBlank(awsAccessKeyVal)) {
            throw new IllegalArgumentException("A valid AWS access key is required");
        }

        if (StringUtils.isBlank(awsSecretKeyVal)) {
            throw new IllegalArgumentException("A valid AWS secret is required");
        }

        if (StringUtils.isBlank(s3BucketVal)) {
            throw new IllegalArgumentException("A valid S3 bucket is required");
        }

        this.dir = Paths.get(directory);
        this.awsAccessKey = awsAccessKeyVal;
        this.awsSecretKey = awsSecretKeyVal;
        this.s3Bucket = s3BucketVal;
    }

    /* (non-Javadoc)
     * @see java.lang.Thread#run()
     */
    @Override
    public void run() {
        try {
            new WatchDirectory(this.dir, this.awsAccessKey,
                    this.awsSecretKey, this.s3Bucket).processEvents();
        }
        catch (final IOException ie) {
            logger.error("I/O exception when watching directory: " + ie.getMessage());
        }
    }
}
