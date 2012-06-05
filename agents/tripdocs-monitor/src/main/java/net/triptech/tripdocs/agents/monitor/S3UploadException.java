package net.triptech.tripdocs.agents.monitor;

/**
 * The Class S3UploadException.
 */
public class S3UploadException extends Exception {

    /** The unique serial version UID for the class. */
    private static final long serialVersionUID = 200331041292945563L;

    /**
     * Constructs an instance of <code>S3UploadException</code>
     * with the specified detail message.
     *
     * @param msg the detail message.
     */
    public S3UploadException(final String msg) {
        super(msg);
    }
}

