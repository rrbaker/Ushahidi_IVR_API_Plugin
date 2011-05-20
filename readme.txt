=== About ===
name: IVR API
website: http://konpagroup.com
description: IVR Api link
version: 0.1
requires: 2.0
tested up to: 2.0
author: George Chamales
author website: http://konpagroup.com

== Description ==

== Installation ==
1. Copy the entire /ivr-aip/ directory into your /plugins/ directory.
2. Activate the plugin.
3. Send messages to api_ivr in the form

http://afghanwells.konpagroup.com/api?task=report&ivrcode=001011&wellwork=Yes&mechanicknow=Yes&mechanicfix=No&filename=CAFG-793400103-001002-20110420222537.wav&resp=xml

Formats: XML, JSON


Parameters:
ivrcode
Required. This is the unique ID of the well, as entered by the caller to identify which well is referenced in the call.

wellwork
Required.  This is a boolean ("Yes" or "No") value to state if the well is functional.

mechanicknow
Optional.  This is a boolean ("Yes" or "No") value to state if the mechanic knows of the issue (if applicable).

mechanicfix
Optional.  This is a boolean ("Yes" or "No") value to state if the mechanic can fix the issue (if applicable).

filename
Optional.  This is the name of the file should the caller leave a message with their update.

== Changelog ==

