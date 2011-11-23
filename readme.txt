=== About ===
name: Ushahidi IVR API Plugin
website: http://watertracker.af
description: Custom API for IVR connection for WaterTracker.af
version: 0.9
requires: 2.0
tested up to: 2.1
author: George Chamales, Rob Baker, John Etherton
author website: http://watertracker.af



== Description ==

The Ushahidi IVR API plugin connects the Roshan (Afghanistan) and one97 (Delhi) IVR system to custom fields creating in WaterTracker's customized Ushahidi platform.



== Installation ==

1. Copy the entire /ivr_api/ directory into your /plugins/ directory.
2. Activate the plugin.
3. Send messages to ivr_api in the form

http://watertracker.af/api_ivr?task=report&ivrcode=001001&phonenumber=1234567890&wellwork=Yes&mechanicknow=Yes&mechanicfix=No&filename=CAFG-793400103-001002-20110420222537.wav&resp=json

Formats: JSON

Note: You will also likely want to change the TZ variable in php.ini to the country timezone:
ex: date.timezone = "Asia/Kabul"


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

0.9   Putting back category updating, changing file paths (ivr_api)
0.8   Refactored the presentation and JS (RB)
0.6   Cosmetic, placement adjust on report pages (JE)
0.5   Big revision to plugin storing data in its own tables, inclusion of Asterisk player
0.3   Including non-API related files for ftp sync (RB)
0.2   Build in support for phonenumber field (RB)
0.1   Initial build (GC)