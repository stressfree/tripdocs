package net.triptech.tripdocs.agents.monitor;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.math.BigInteger;
import java.net.URLEncoder;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.attribute.BasicFileAttributes;
import java.nio.file.attribute.FileTime;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

import org.apache.commons.lang.StringUtils;
import org.apache.log4j.Logger;
import org.jets3t.service.S3Service;
import org.jets3t.service.S3ServiceException;
import org.jets3t.service.acl.AccessControlList;
import org.jets3t.service.impl.rest.httpclient.RestS3Service;
import org.jets3t.service.model.S3Bucket;
import org.jets3t.service.model.S3Object;
import org.jets3t.service.security.AWSCredentials;

/**
 * The Class S3Uploader.
 */
public class S3Uploader {

    /** The Constant logger. */
    private final static Logger logger = Logger.getLogger(S3Uploader.class);

    /**
     * Upload the file to Amazon S3. Returns the URL for the uploaded file.
     *
     * @param path the path
     * @param s3Bucket the s3 bucket
     * @param awsAccessKey the aws access key
     * @param awsSecretKey the aws secret key
     * @return the string
     * @throws S3UploadException the s3 upload exception
     */
    public static final String upload(final Path path, final String s3Bucket,
            final String awsAccessKey, final String awsSecretKey)
            throws S3UploadException {

        String url = "";

        if (StringUtils.isBlank(awsAccessKey) || StringUtils.isBlank(awsSecretKey)) {
            throw new IllegalArgumentException("AWS credentials are required");
        }

        try {
            BasicFileAttributes attr = Files.readAttributes(path,
                    BasicFileAttributes.class);

            if (attr.isRegularFile()) {
                // Upload the file to S3 - inherit bucket ACL.
                logger.info("File to upload: " + path.getFileName().toString());
                logger.info("File last modified time: " + attr.lastModifiedTime());

                String name = getFileNameString(path);
                String directory = getDirectoryHash(attr.lastModifiedTime(), name);
                String key = directory + "/" + name;

                logger.info("S3 key to be created: " + key);

                AWSCredentials awsCredentials =  new AWSCredentials(
                        awsAccessKey, awsSecretKey);

                S3Service s3Service = new RestS3Service(awsCredentials);

                S3Bucket bucket = s3Service.getBucket(s3Bucket);

                S3Object fileObject = new S3Object(path.toFile());
                fileObject.setKey(key);
                fileObject.setName(key);
                fileObject.setAcl(AccessControlList.REST_CANNED_PUBLIC_READ);
                s3Service.putObject(bucket, fileObject);

                url = "http://" + s3Bucket + ".s3.amazonaws.com/" + key;
            }
        } catch (NoSuchAlgorithmException nse) {
            throw new S3UploadException("Missing encyrption algorithm");
        } catch (IOException ioe) {
            throw new S3UploadException("File read error: " + ioe.getMessage());
        } catch (S3ServiceException s3e) {
            throw new S3UploadException("Error uploading to S3: " + s3e.getMessage());
        }
        return url;
    }

    /**
     * Gets the file name string.
     *
     * @param path the path
     * @return the file name
     */
    private static String getFileNameString(Path path) {

        String name = StringUtils.replace(path.getFileName().toString(), " ", "_");

        try {
            name = URLEncoder.encode(name, "UTF-8");
        } catch (UnsupportedEncodingException ue) {
            logger.error("UTF-8 encoder not available: " + ue.getMessage());
        }
        return name;
    }

    /**
     * Create a directory hash.
     *
     * @param time the time in milliseconds
     * @param fileName the file name
     * @return the string
     */
    private static String getDirectoryHash(FileTime fileTime, String fileName) {

        String hash = "";
        String key = String.valueOf(fileTime.toMillis()) + "_" + fileName;

        try {
            MessageDigest m = MessageDigest.getInstance("MD5");
            m.update(key.getBytes(), 0, key.length());
            hash = new BigInteger(1, m.digest()).toString(12);
        } catch (NoSuchAlgorithmException nse) {
            System.out.println("The MD5 algorythm is not available: " + nse.getMessage());
        }
        return hash;
    }

}
