/*******************************************************************************
 * Copyright (c) 2012 David Harrison, Triptech Ltd.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the GNU Public License v3.0
 * which accompanies this distribution, and is available at
 * http://www.gnu.org/licenses/gpl.html
 *
 * Contributors:
 *     David Harrison, Triptech Ltd - initial API and implementation
 ******************************************************************************/
package net.triptech.tripdocs.agents.monitor;

import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.Calendar;
import java.util.HashMap;

import org.apache.commons.lang.StringUtils;
import org.apache.log4j.Logger;

/**
 * The Class UploadThread.
 */
public class UploadThread extends Thread {

    /** The Constant SLEEP_TIME. */
    private final static int SLEEP_TIME = 10000;

    /** The Constant THIRTY_SECONDS. */
    private final static long THIRTY_SECONDS = 30000l;

    /** The Constant logger. */
    private final static Logger logger = Logger.getLogger(UploadThread.class);

    /** The file map. */
    private final HashMap<String, Long> fileMap = new HashMap<String, Long>();

    /** The aws access key. */
    final String awsAccessKey;

    /** The aws secret key. */
    final String awsSecretKey;

    /** The s3 bucket. */
    final String s3Bucket;

    /** The stop running. */
    private boolean stopRunning = false;

    /**
     * Instantiates a new upload thread.
     *
     * @param awsAccessKeyVal the aws access key val
     * @param awsSecretKeyVal the aws secret val
     * @param s3BucketVal the s3 bucket val
     */
    public UploadThread(final String awsAccessKeyVal,
            final String awsSecretKeyVal, final String s3BucketVal) {

        if (StringUtils.isBlank(awsAccessKeyVal)) {
            throw new IllegalArgumentException("A valid AWS access key is required");
        }

        if (StringUtils.isBlank(awsSecretKeyVal)) {
            throw new IllegalArgumentException("A valid AWS secret is required");
        }

        if (StringUtils.isBlank(s3BucketVal)) {
            throw new IllegalArgumentException("A valid S3 bucket is required");
        }

        this.awsAccessKey = awsAccessKeyVal;
        this.awsSecretKey = awsSecretKeyVal;
        this.s3Bucket = s3BucketVal;
    }

    /**
     * Stop running the loop.
     */
    public final void stopRunning() {
        this.stopRunning = true;
    }

    /**
     * Adds the file to the map.
     *
     * @param path the path
     */
    public final void addFile(final String path) {
        this.fileMap.put(path, Calendar.getInstance().getTimeInMillis() + THIRTY_SECONDS);
    }

    /**
     * Removes the file from the map.
     *
     * @param path the path
     */
    public final void removeFile(final String path) {
        if (this.fileMap.containsKey(path)) {
            this.fileMap.remove(path);
        }
    }

    /* (non-Javadoc)
     * @see java.lang.Thread#run()
     */
    @Override
    public void run() {
        while (!stopRunning) {
            HashMap<?, ?> clonedMap = (HashMap<?, ?>) this.fileMap.clone();

            for (Object key : clonedMap.keySet()) {
                final String fileName = (String) key;
                final Long modifiedTime = this.fileMap.get(fileName);

                if (Calendar.getInstance().getTimeInMillis() > modifiedTime) {
                    logger.error("Uploading file: " + fileName);

                    // Upload the file to S3.
                    try {
                        Path path = Paths.get(fileName);
                        String url = S3Uploader.upload(path, s3Bucket,
                                awsAccessKey, awsSecretKey);

                        logger.info("File uploaded to: " + url);

                        // Remove the file from the map if successfully uploaded
                        // and the file has not been modified since the upload began.
                        final long endModifiedTime = this.fileMap.get(fileName);

                        logger.info("Process start modified time: " + modifiedTime);
                        logger.info("Process end modified time: " + endModifiedTime);

                        if (modifiedTime == endModifiedTime) {
                            this.fileMap.remove(fileName);
                        }
                    } catch (S3UploadException s3e) {
                        logger.error("Error uploading to S3: " + s3e.getMessage());
                    }
                }
            }
            try {
                Thread.sleep(SLEEP_TIME);
            } catch (InterruptedException ie) {
                logger.info("Thread sleep interrupted: " + ie.getMessage());
            }
        }
    }
}
