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

