=== About ===
name: USAID IVR API
website: http://konpagroup.com
description: Custom API for IVR connection
version: 0.3
requires: 2.0
tested up to: 2.0
author: George Chamales, Rob Baker
author website: http://konpagroup.com



== Description ==

The USAID IVR API connects the Roshan (Afghanistan) and one97 (Delhi) IVR system to custom fields creating in the WaterTracker (watertracker.af) Ushahidi system.



== Installation ==

1. Copy the entire /ivr-api/ directory into your /plugins/ directory.
2. Activate the plugin.
3. Send messages to ivr_api in the form

http://watertracker.af/api_ivr?task=report&ivrcode=001001&phonenumber=1234567890&wellwork=Yes&mechanicknow=Yes&mechanicfix=No&filename=CAFG-793400103-001002-20110420222537.wav&resp=json

Formats: XML, JSON



== Parameters ==

ivrcode
Required. This is the unique ID of the well, as entered by the caller to identify which well is referenced in the call.

phonenumber
Required.  This is the phone number of the caller.

wellwork
Required.  This is a boolean ("Yes" or "No") value to state if the well is functional.

mechanicknow
Optional.  This is a boolean ("Yes" or "No") value to state if the mechanic knows of the issue (if applicable).

mechanicfix
Optional.  This is a boolean ("Yes" or "No") value to state if the mechanic can fix the issue (if applicable).

filename
Optional.  This is the name of the WAV file should the caller leave a message with their update.



== Changelog ==

0.3 	Including non-API related files for ftp sync (RB)
0.2 	Build in support for phonenumber field (RB)
0.1 	Initial build (GC)