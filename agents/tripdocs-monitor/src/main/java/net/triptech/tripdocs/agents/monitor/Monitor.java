package net.triptech.tripdocs.agents.monitor;

import org.apache.commons.lang.StringUtils;
import org.apache.log4j.BasicConfigurator;
import org.apache.log4j.Logger;
import org.apache.log4j.PropertyConfigurator;

/**
 * The Class Monitor.
 */
public class Monitor {

    private final static String DEFAULT_DIRECTORY = "/tmp/test";

    private final static Logger logger = Logger.getLogger(Monitor.class);

    /**
     * The main method.
     *
     * @param args the command line arguments
     */
    public static void main(final String[] args) {

        System.out.println("Tripdocs Monitor starting up...");

        String directory = "", log4jConfig = "";

        if (args.length > 0) {
            directory = args[0];
        }
        if (args.length > 1) {
            log4jConfig = args[1];
        }

        if (StringUtils.isBlank(directory)) {
            directory = DEFAULT_DIRECTORY;
        }

        if (StringUtils.isBlank(log4jConfig)) {
            BasicConfigurator.configure();
        } else {
            PropertyConfigurator.configure(log4jConfig);
        }

        WatchThread wt = new WatchThread(directory, true);
        wt.start();

        try {
            wt.join();
        } catch (InterruptedException ie) {
            logger.error("Watch thread interrupted: " + ie.getMessage());
        }
        System.out.println("...closing down the Tripdocs Monitor");
    }
}
