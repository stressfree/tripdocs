<!--[if IE]><![endif]-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7 ]><html class="ie ie6" xmlns="http://www.w3.org/1999/xhtml" lang="en-NZ"><![endif]-->
<!--[if IE 7 ]><html class="ie ie7" xmlns="http://www.w3.org/1999/xhtml" lang="en-NZ"><![endif]-->
<!--[if IE 8 ]><html class="ie ie8" xmlns="http://www.w3.org/1999/xhtml" lang="en-NZ"><![endif]-->
<!--[if IE 9 ]><html class="ie9" xmlns="http://www.w3.org/1999/xhtml" lang="en-NZ"><![endif]-->
<!--[if gt IE 9]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-NZ"><!--<![endif]-->
<head>
    <title><?php echo isset($title) ? $title.' - '.lang('website_title') : lang('website_title'); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta content="<?php echo lang('website_copyright_head'); ?>" name="copyright">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo base_url() ?>favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url().RES_DIR; ?>/img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url().RES_DIR; ?>/img/apple-touch-icon-72x72-precomposed.png">  
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url().RES_DIR; ?>/img/apple-touch-icon-57x57-precomposed.png">
    <link rel="stylesheet" href="<?php echo base_url().RES_DIR; ?>/css/styles.css" type="text/css" />
</head>
<body <?php if ($this->authentication->is_signed_in()) : ?>class="authenticated"<?php endif; ?>>