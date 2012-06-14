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

import java.io.FileInputStream;
import java.io.IOException;
import java.util.Properties;

import org.apache.commons.lang.StringUtils;
import org.apache.log4j.BasicConfigurator;
import org.apache.log4j.Logger;
import org.apache.log4j.PropertyConfigurator;

/**
 * The Class Monitor.
 */
public class Monitor {

    private final static Logger logger = Logger.getLogger(Monitor.class);

    /**
     * The main method.
     *
     * @param args the command line arguments
     */
    public static void main(final String[] args) throws IOException {

        System.out.println("Tripdocs Monitor starting up...");

        String configFile = "";

        if (args.length > 0) {
            configFile = args[0];
        }

        Properties config = new Properties();
        try {
            config.load(new FileInputStream(configFile));
        } catch (IOException ioe) {
            throw new IOException("A valid properties file was not found: "
                    + ioe.getMessage());
        }

        String directory = config.getProperty("directory");
        String log4jConfig = config.getProperty("log4jConfig");
        String awsAccessKey = config.getProperty("awsAccessKey");
        String awsSecretKey = config.getProperty("awsSecretKey");
        String s3Bucket = config.getProperty("s3Bucket");


        if (StringUtils.isBlank(log4jConfig)) {
            BasicConfigurator.configure();
        } else {
            PropertyConfigurator.configure(log4jConfig);
        }

        WatchThread wt = new WatchThread(directory, awsAccessKey, awsSecretKey, s3Bucket);
        wt.start();

        try {
            wt.join();
        } catch (InterruptedException ie) {
            logger.error("Watch thread interrupted: " + ie.getMessage());
        }
        System.out.println("...closing down the Tripdocs Monitor");
    }
}
