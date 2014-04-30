<?php
  /*
  Module: Information Pages Unlimited
  		  File date: 2003/03/02
		  Based on the FAQ script of adgrafics
  		  Adjusted by Joeri Stegeman (joeri210 at yahoo.com), The Netherlands

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  */

  define('ACTION_INFORMATION', 'Action');
  define('ACTIVATION_ID_INFORMATION', 'Activation of the information ID=');
  define('ADD_INFORMATION', 'Add new information');
  define('ADD_QUEUE_INFORMATION', 'Add Travel Agency');
  define('ALERT_INFORMATION', 'Empty: You currently have no files!');
  define('ANSWER_INFORMATION', 'Answer');
  define('CONFIRM_INFORMATION', 'Confirm');
  define('DEACTIVATION_ID_INFORMATION', 'Deactivation of the Agency ID=');
  define('DELETE_INFORMATION', 'Delete');
  define('DELETE_CONFITMATION_ID_INFORMATION', 'Delete Confirmation Agency ID=');
  define('DELETE_ID_INFORMATION', 'Delete the Agency ID=');
  define('DELETED_ID_INFORMATION', 'Deleted the Agency ID=');
  define('DESCRIPTION_INFORMATION', 'Description');
  define('EDIT_ID_INFORMATION', 'Edit the Agency ID=');
  define('EDIT_INFORMATION', 'Edit');
  define('ERROR_20_INFORMATION', 'You have not defined a valid value for option <b>Queue</b>. You can only define a numeric value');
  define('ERROR_80_INFORMATION', 'You did not fill all <b>necessary fields</b>');
  define('INFORMATION_ID_ACTIVE', 'This information is ACTIVE');
  define('INFORMATION_ID_DEACTIVE', 'This information is NOT ACTIVE');
  define('INFORMATION_ACTIVE', 'Active');
  define('INFORMATION_DEACTIVE', 'Inactive');
  define('ID_INFORMATION', 'ID');
  define('TITLE_INFORMATION', 'Contact Person Name');
  define('TITLE_AGENCY_CODE', 'Agency Code');
  define('DESCRIPTION_INFORMATION', 'Description');
  define('QUEUE_INFORMATION', 'Queue');
  define('QUEUE_INFORMATION_LIST', 'Queue List: ');
  define('MANAGER_INFORMATION', 'Travel Agencies');
  define('NO_INFORMATION', 'No');
  define('AGENCY_NAME_INFORMATION','Agency Name');
  define('PUBLIC_INFORMATION', 'Public');
  define('PAGE_PREVIEW', 'Preview');
  define('QUEUE_INFORMATION', 'Order');
  define('QUEUE_INFORMATION_LIST', 'Order: ');
  define('SORT_BY', 'Information Page - Sort by');
  define('STATUS_INFORMATION', 'Status:');
  define('SUCCED_INFORMATION', ' Successful');
  define('TITLE', 'Information System');
  define('TITLE_INFORMATION', 'Title:');
  define('VIEW_INFORMATION', 'Information View');
  define('VISIBLE_INFORMATION', 'Visible');
  define('VISIBLE_INFORMATION_DO', '( To Do visible )');
  define('UPDATE_ID_INFORMATION', 'Update of Travel Agency No : ');
  define('WARNING_INFORMATION', 'Warning');
  
  define('TEXT_AGENCY_ADDRESS', 'Address:');
define('TEXT_AGENCY_CITY', 'City:');
define('TEXT_AGENCY_STATE', 'State:');
define('TEXT_AGENCY_ZIP', 'Zip:');
define('TEXT_AGENCY_COUNTRY', 'Country:');
define('TEXT_AGENCY_PHONE', 'Reservation Phone Number:');
define('TEXT_AGENCY_NAME', 'Agency Name:');
define('TEXT_AGENCY_CONTACT_PERSON', 'Contact Person:');
define('TEXT_AGENCY_OPERATOR_LANGUAGE', 'Agency Operator Language:');
define('PHONE_FAX_NO', 'Contact Number');
define('TEXT_OPERATOR_LANGUAGE', 'Languages');
define('TEXT_HEADING_EMAIL_ADDRESS', 'E-mail Address');
define('TEXT_HEADING_ADDRESS', 'Address');
define('TEXT_HEADING_WEB_URL', 'Website URL');
define('TEXT_AGENCY_WEBSITE_URL', 'Website URL:');
define('TEXT_AGENCY_MAJOR_CATEGORIES', 'Major Categories');
define('TEXT_AGENCY_LAST_UPDATE_BY_WHOM', 'Last Update/By');
define('TEXT_AGENCY_TIME_ZONE','Agency Timezone:');
define('TEXT_AGENCY_LAST_UPDATE_BY', 'Last Update/By Whom:');
define('TEXT_AGENCY_MAJOR_CATEGORIES', 'Major Categories');
define('TEXT_AGENCY_NEXT_DUE_DATE', 'Next Update Due Date:');
define('TEXT_HEDING_AGENCY_NEXT_UPDATE_DUE_DATE', 'Next Update Due Date');

define('TITLE_ENCODED_AGENCY_CODE', 'Encoded Agency Code');
define('AGENCY_TIMEZONE','Time Zone');
define('TITLE_OPERATE_CURRENCY','Operate Currency:');
define('TEXT_AGENCY_DEFUAL_MAX_ALLOW_CHILD_AGE','Default Max Allow Child Age');
define('TITLE_TRASACTION_FEE',' Transaction Fee');

define('CATEGORY_GENERAL', 'General');
define('CATEGORY_RESERVATION', 'Reservation');
define('CATEGORY_ACCOUNTING', 'Accounting');
define("TTL_EMERGENCY_CONTACT_PERSON", "Emergency Contact Person:");
define("TTL_EMERGENCY_PHONE_NO", "Emergency Phone No:");
define("TTL_PROVIDER_CXLN_POLICY", "Provider CXLN Policy:");
define("TTL_CXLN_POLICY", STORE_OWNER.' CXLN Policy:');
define("TXT_PAYMENT_METHOD", "Payment Method:");
define("TXT_PAYMENT_FREQUENCY", "Payment Frequency:");
define("TXT_ACC_NOTES", "Notes");
define("TXT_SELECT", "---Select---");
define("OPT_ACH", "ACH");
define("OPT_CHECK", "Check");
define("OPT_WIRE_TRANSFER", "Wire Transfer");
define("OPT_MONTHLY", "Monthly");
define("OPT_SEMI_MONTHLY", "Semi-monthly");
define("OPT_OTHER", "Other");
define('ERROR_50_INFORMATION', 'E-mail address is already exists, please try another');
define('TEXT_LOGIN_DETAIL','Login Detail');
define('TEXT_PROVIDERS_EMAIL', 'Providers login email:');
define('TEXT_PROVIDERS_PASSWORD', 'Providers password:');
define('TEXT_PROVIDERS_PASSWORD_CONF', 'Confirm password:');
define('TEXT_INFO_ERROR', '<font color="red">Email address has already been used! Please try again.</font>');
define('TEXT_SELECT_AGENCY_ERROR', '<font color="red">Please select agency.</font>');
define('TEXT_DATE_ACCOUNT_CREATED', 'Account Created:');
define('TEXT_INFO_DATE_LAST_LOGON', 'Last Logon:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Number of Logons:');
define('TEXT_PROVIDER_AGENCY', 'Agency:');
define('SELECTED_NONE', '--- New Provider ---');
define('PROVIDER_EMAIL_SUBJECT', 'New provider');
define('PROVIDER_EMAIL_TEXT', 'Hi,' . "\n\n" . 'You can access your account with the following password. Once you access your account, please change your password!' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Thanks!' . "\n" . '%s' . "\n\n" . 'This is a system automated response, please do not reply, as it would be unread!');
define('PROVIDER_EMAIL_EDIT_SUBJECT', 'Provider profile edit');
define('PROVIDER_EMAIL_EDIT_TEXT', 'Hi,' . "\n\n" . 'Your personal information has been updated by an administrator.' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Thanks!' . "\n" . '%s' . "\n\n" . 'This is a system automated response, please do not reply, as it would be unread!');
define("TXT_DEL_PROVIDERS_ACC", "Delete Provider's Account");
define("TXT_CANCEL_DEL", "Cancel");
define("TXT_WARNING_DELETE_PROVIDER", "Are you sure to permanently delete this providers account? &nbsp; ");
define('DELETED_PROVIDER_INFORMATION', 'Deleted the Provider Account ID=');
define('TEXT_PROVIDERS_START_DATE', 'Start Date:');
define('TEXT_PROVIDERS_DISPLAY_STATUS_HIST', 'Use Provider Account:');
define('TEXT_SEPARATE_TOUR_AND_TOUR_PACKAGE', 'Separate Tour and Tour Package:');
define('PROVIDER_MAIL_FROM', '走四方網');
define('TXT_CAN_ISSUE_E_TICKET', 'Can Issue E-Ticket:');
define('CATEGORY_ETICKET', 'E-Ticket');
define('TXT_ETICKET_DEFAULT_COMMENT', 'E-Ticket Default comment:');
define('TEXT_ORDER_CHARGE_YES','Yes');
define('TEXT_ORDER_CHARGE_NO','No');
define('TEXT_ORDER_AUTO_CHARGE_HEAD','Order Auto Charged for Tours?');
define('TEXT_ORDER_AUTO_CHARGE_HEAD_PACKAGES','Order Auto Charged for Tour Packages?');
define('TEXT_PROVIDERS_SUB_ACCOINTS', 'Provider\'s sub-accounts:');
define('TXT_EMAIL_NOTICE','Allow user to receive email notice?');
define('ERROR_100_INFORMATION', 'Please enter valid <b>Providers login email address</b>');
define("TTL_LOCAL_OPERATOR_PHONE_NO", "Local Operator Phone No.:");
?>