=== Wise Chat Pro ===
Contributors: Kainex
Tags: chat, plugin, ajax, javascript, shortcode, social, widget, responsive, chat plugin, chatbox, shoutbox, wordpress chat, online chat, social, chat software, webchat, free chat, community, visitor chat, im chat, pure chat, web chat, wordpress plugin, instant message, messaging, smiles, guestbook, themes, communication, contact
Requires at least: 3.6
Tested up to: 5.6
Stable tag: 2.5.5

Advanced chat plugin for WordPress. It requires no server, supports multiple channels, bad words filtering, appearance settings, bans and more.

== Description ==

Wise Chat Pro is a chat plugin that helps to build a social network and to increase user engagement of your website by providing possibility to exchange real time messages in chat rooms. The plugin is easy installable and extremely configurable. It also has a growing list of features and the constant support.

**[See more](https://kaine.pl/projects/wp-plugins/wise-chat-pro)**

= List of features: =

* **Easy installation**: The chat can be installed using a convenient shortcode, widget or PHP function. Multiple chat installations can be embedded on the same page.
* **Mobile ready**: Fully responsive design and mobile ready interface.
* **Themes**: Use one of 6 nice themes to quickly adjust the style of the chat. The simple theme adjusts to your styles.
* **No server required**: There is no need to set up, maintain or pay for a server. Regular hosting is sufficient.
* **Multisite compatibility**: Install Wise Chat in multisite environment.
* **Private messages**: Allow your users to talk to each other in private conversations.
* **Chat Channels**: Let people post messages in dedicated chat rooms (called channels). Run multiple channels either simultaneously on the same page or on separate pages.
* **Private Chat Channels**: Make individual chat channels private by protecting them with a password. Only users that know the password could access the channel.
* **Channels management**: Remove all messages from the single channel or delete the channel completely.
* **Anonymous Users**: Unregistered or anonymous users can be allowed to participate in the chat. Auto-generated name (configurable) is assigned to such user.
* **Anonymous Users with custom name**: Let users to choose their name when they visit the chat for the first time.
* **Registered Users**: Registered and logged-in users are properly recognized by the plugin.
* **External authentication**: Let your users authenticate using their Facebook, Twitter or Google account.
* **Single sign-on**: No separate login to the chat is required. If an user is logged-in to your website, then the chat automatically logs her / him in.
* **Read-only mode**: Make the chat read-only for anonymous users. Encourage your users to register in order to post messages.
* **Users limit in channels**: Limit the amount of users allowed to participate in a channel.
* **Channels limit for an user**: Limit the amount of channels that an user can participate simultaneously.
* **User settings**: Chat users have options to change their name, color of their messages or mute sounds generated by the chat.
* **Avatars**: Each user is identified with an avatar.
* **Restrict access**: Option to permit access for registered and logged-in users only.
* **Flood Control**: Detect spammers and block them from posting messages in the chat.
* **Posting links and images**: Option to allow clickable links and images. Posted images are downloaded into Media Library.
* **Pictures from camera**: On mobile devices users can take a picture directly and post it with a message.
* **YouTube movies**: Display links to YouTube movies as a video player with adjustable width and height.
* **File attachments**: Attach files directly to the messages using convenient uploader.
* **Multiline messages**: Let your users post long messages in multiple lines. Keyboard shortcut Shift+Enter can be used to send such messages.
* **Notifications**: Notifications signalizing new messages. It can be a sound or a counter in browser's title.
* **Messages auto cleanup**: Auto cleanup messages older than defined amount of time.
* **Localization**: Provide a translation for your language. All texts are translatable.
* **Appearance adjustments**: Adjust visual appearance to match your website by editing colors, font sizing, showing / hiding interface elements, changing sort order of messages and positioning chat elements. All this can be done using settings page.
* **Chat Moderation**: Protect your chat by enabling swearing control using built-in bad words filter. It supports English and Polish languages.
* **Chat Channel Statistics Shortcode**: Display basic statistics of a channel using the shortcode.
* **Messages filtering**: Create rules that detect and replace specific words / patterns in posted messages. It is good for cutting links for example.
* **Messages history**: All recently published messages are available in input field for resending.
* **Chat Opening Hours / Days**: Define days and range of hours when the chat is open for users.
* **Spam Reporting**: Chat participants can report spam messages.
* **Bans**: Administrator can ban (IP based) users who do not follow rules of the chat.
* **Kicks**: Administrator can kick (IP based) users who do not follow rules of the chat.
* **Automatic bans**: An option that automatically blocks an user if he / she exceeds the defined number of bad words.
* **Moderation**: Administrators (or other specified user roles) can delete single messages or ban users that violate chat rules.
* **Emoticons**: See nice smiles in messages by enabling emoticons support. Emoticons can be inserted either using shortcuts or the list.
* **Custom CSS**: Custom CSS styles can be applied for each element of the chat.
* **Channels statistics**: Observe various statistics of channels, including amount of posted messages or users online.
* **Detailed configuration page**: All features can be configured on the settings page. You can control messages posting, appearance, channels statistics, filters, bans and localization.
* **Twitter hash tags**: Detecting Twitter hash tags and converting them into links.
* **List of current users**: Show the list of online users and their national flag / country / city of origin in the sidebar of the chat. Display amount of online users.
* **Backups**: Backup all messages from given channel by downloading them as a single CSV file.

All settings are available on `Settings -> Wise Chat Pro Settings` page.

== Installation ==

= Requirements: =
* Exif PHP extension (in order to run auto-rotate feature for sent images)
* PHP 5 >= 5.4.0 (it will run on 5.2.0 but opening hours feature will not work)
* openssl module for PHP (in most cases it is already installed)
* jQuery JS library (available in most themes)

= Optional requirements: =
* mbstring PHP extension (in order to use all features of bad words filter)
* Lightbox 2 library (for showing images on the nice looking popup)

= Installation: =
1. Upload the entire `wise-chat-pro` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Place a shortcode `[wise-chat]` in your posts or pages. See below for details.
1. Alternatively install it in your templates via `<?php if (function_exists('wise_chat')) { wise_chat(); } ?>` code.
1. Alternatively install it using dedicated widget in `Appearance -> Widgets`, it's called `Wise Chat Window`. See below for details.

= Post Installation Notices: =
* After installation go to Settings -> Wise Chat Pro Settings page, select Localization tab and translate all the messages into your own language.
* Posting pictures from camera / local storage is limited to the specific range of Web browsers. See FAQ for details.

= Widget installation: =

You can install Wise Chat using dedicated widget. Go to `Appearance -> Widgets` and add `Wise Chat Window` widget to a sidebar. In the widget's settings you can specify:

* **Channel**: It is a name of the channel to open in the chat window. Channel will be created on first use.
* **Shortcode options**: It is a space-separated list of options that configure Wise Chat. All shortcode attributes could be used here, type for example `theme="colddark" show_users="0"` in order to choose Cold Dark theme and disable list of users.

For full documentation of the shortcode attributes visit the website:

**[Shortcode documentation](https://kaine.pl/projects/wp-plugins/wise-chat-pro/documentation/shortcode)**

= Shortcode installation: =

You can install Wise Chat using dedicated shortcode. In basic form it is just:
`[wise-chat]`
It will open chat window on the default channel called "global". If you would like to open the chat on custom channel called "My Room" try this:
`[wise-chat channel="My Room"]`
The shortcode can be configured with the list of attributes, for example – in order to enable Cold Dark theme and to show list of users you can type:
`[wise-chat theme="colddark" show_users="1"]`

Almost all settings available on plugin's settings page are also available in shortcode. For full documentation of the shortcode visit the website:

**[Shortcode documentation](https://kaine.pl/projects/wp-plugins/wise-chat-pro/documentation/shortcode)**

== Frequently Asked Questions ==

= How to create a channel and open it? =

Place the following short code in your page or post:
`[wise-chat channel="my-channel-name"]`
or add this PHP snippet in the theme's source file:
`<?php if (function_exists('wise_chat')) { wise_chat('my-channel-name'); } ?>`
or put "Wise Chat Window" widget on the desired sidebar and set the desired channel name in Channel field.

The channel will be created during the first usage and it will be added to the list of channels on Wise Chat Pro Settings page in the Channels tab.

= How to create a password-protected private channel? =

First, create a regular channel (see the previous question) and then go to Settings -> Wise Chat Pro Settings page, select Channels tab. In the right to the chosen channel click "Password" link and a form will appear below. Specify the password and confirm it by clicking "Set Password" button. From now the channel is secured and only users that enter valid password are allowed to join. 

= How to convert a password-protected channel into an open channel? =

Go to Settings -> Wise Chat Pro Settings page, select Channels tab. In the right to the chosen channel click "Password" link and a form will appear below. Click "Delete Password" button.

= Is there a way to show simple statistics regarding chat channel separately in a post or a page? =

Yes. Place "wise-chat-channel-stats" shortcode in the page or post. You can choose what to display using "template" attribute. Here is a fully-featured example:
`[wise-chat-channel-stats channel="my-channel" template="Channel: {channel} Messages: {messages} Users: {users}"]`

* channel - displays channel's name
* messages - displays the amount of total messages in the channel
* users - displays the amount of total active users in the channel

= How to install the chat using the widget? =

Go to Appearance -> Widgets page, drag and drop "Wise Chat Window" widget on the desired sidebar. The name of the channel can be specified as well.

= I can't see translation for my language. How to localize the chat for end-user? =

You can do it by yourself. Go to Settings -> Wise Chat Pro Settings page, select Localization tab and translate texts in each field into your own language. These are all texts that are exposed to end-users.

= What about the support for mobile devices and responsiveness? =

Wise Chat plugin works on any mobile device that supports Javascript and cookies. The interface is responsive, but you should enable submit button in order an user could send a message. Go to Settings -> Wise Chat Pro Settings page, select Appearance tab and select checkbox "Show Submit Button". 

= The chat generates anonymous names for every new user. How to force every user to choose their name on first use? =

You can enable a form that appears every time a new user tries to enter the chat. The form displays username field and allows to enter the chat only if the user type his/her name. Settings -> Wise Chat Pro Settings page, select General tab and select option "Force Username Selection".

= How does the bad words filter work? =

The plugin has its own implementation of bad words filtering mechanism. Currently it supports two languages: English and Polish. It is turned on by default. It detects not only simple words but also variations of words like: "H.a.c_ki.n_g" (assuming that "hacking" is a bad word).

= How to ban an user? =

Log in as an administrator and go to Settings -> Wise Chat Pro Settings page, select Moderation tab. Enable "Enable Admin Actions" option and go to a page containing the chat. Next to each message there should be a button that allows to ban an user for 1 day.

Alternatively log in as an administrator and type the command:
`/ban [UserName] [Duration]`
where "UserName" is the chosen user's name and "Duration" is constructed as follows: 1m (a ban for 1 minute), 7m (a ban for 7 minutes), 1h (a ban for one hour), 2d (a ban for 2 days), etc. Notice: IP addresses are actually blocked. 

Alternatively you can go to Settings -> Wise Chat Pro Settings page, select Bans tab. In "New Ban" row specify: IP address, duration and finally click "Add Ban" button.

= How to get the list of banned users? =

Log in as an administrator and type the command:
`/bans`
or go to Settings -> Wise Chat Pro Settings page and select Bans tab.

= How to remove a ban of an user? =

Log in as an administrator and type the command:
`/unban [IP address]`
or go to Settings -> Wise Chat Pro Settings page, select Bans tab and then delete the desired ban from the list.

= How to kick users? =

Log in as an administrator and go to Settings -> Wise Chat Settings page, select Moderation tab. Enable "Enable Admin Actions" option and go to chat page. Next to each message there should be a button for kicking the user who sent the message.

Alternatively, you can go to Settings -> Wise Chat Settings page and select Kicks tab. In "Add Kick" section specify IP address and click "Add Kick" button.

= How to get the list of kicked users? =

Log in as an administrator, go to Settings -> Wise Chat Settings page and select Kicks tab.

= How to remove kicked IP address? =

Log in as an administrator, go to Settings -> Wise Chat Settings page, select Kicks tab and then delete the desired IP address from the list.

= How to get some information (e.g. IP address) about an user? =

Log in as an administrator and type the command:
`/whois [UserName]`
where "UserName" is the chosen user's name.

= How to use messages history feature? =

Click on the message input field and use arrow keys (up and down) to scroll through the history of recently sent messages.

= How to prevent from accessing the chat by anonymous users? =

Go to Settings -> Wise Chat Pro Settings page, select General tab and select "Only regular WP users" in "Access Mode" combo box. From now an user has to be logged in as a regular WordPress user in order to gain access to the chat.

= How does auto-ban feature work? =

There is a counter for each user. Every time an user types a bad word in a message the counter is increased. If it reaches the threshold (by default set to 3) the user is banned for 1 day (the duration is configurable).

= How to delete a single message from the channel? =

Log in as an administrator. Go to Settings -> Wise Chat Pro Settings, select Moderation tab and enable "Enable Admin Actions" option. From now every message in every channel has its own delete button ("X" icon). The button appears only for logged in administrators (or other choosen role). Use the button to delete desired messages.

= How to give message deletion permission to certain user roles? =

Log in as an administrator. Go to Settings -> Wise Chat Pro Settings, select Moderation tab and enable "Enable Admin Actions" option. Next, in "Delete Message Permission" field select a role that is allowed to delete messages. From now users belonging to the selected role have permission to delete messages.

Alternatively: add "wise_chat_delete_message" capability to a role you want to have that permission. It could be either standard WordPress role or a custom role. 

= How does "Enable Images" option actually work? =

If you enable "Enable Images" option every link posted in the chat which points to an image will be converted into image. The image will be downloaded into Media Library and then displayed on the chat window. Those downloaded images will be removed from Media Library together with the related chat messages (either when removing all messages or a single one). If an image cannot be downloaded the regular link is displayed instead. 

= Option "Enable Images" does not work. I see regular hyperlinks instead of images. What is wrong? =

The option requires a few prerequisites in order to operate correctly: GD and Curl extensions must be installed, Media Library must operate correctly, posted image link must have a valid extension (jpg, jpeg, gif  or png), HTTP status code of the response must be equal 200, image cannot be larger than 3MB (the default limit that can be adjusted in settings). Try to read PHP logs in case of any problems. 

= What if I would like the images to be opened in a popup layer using Lightbox? =

By default all images open using Lightbox 2 library but only if the library is installed within current theme or a dedicated plugin. Without Lightbox 2 each image opens in the new tab / window.

= I have installed a plugin containing Lightbox library and newly added messages are not displayed on the Lightbox layer. What is wrong?

If you installed a plugin that incorporates Lightbox library and it still doesn't work make sure that the plugin uses original Lightbox 2 Javascript library. The chat is compatible only with the original Lightbox 2 library. 

= Image uploader does not work. What is wrong? =

Uploading of images is supported in the following Web browsers: IE 10+, Firefox 31+, Chrome 31+, Safari 7+, Opera 27+, iOS Safari 7.1+, Android Browser 4.1+, Chrome For Android 41+.

= How to replace specific phrase in every message posted by users? =

You can use filters feature. Go to Settings -> Wise Chat Pro Settings, select Filters tab and add new filter. From now each occurrence of the phrase will be replaced by the defined text in every message that is posted to any chat channel.

= Chat window is showing up but it does not work. I cannot receive or send messages. What is wrong? =

Ensure that jQuery library is installed in your theme. Wise Chat cannot operate without jQuery. 

= Wise Chat plugin is making a lot of long-running HTTP requests. How to improve the performance? =

Every 3 seconds the plugin checks for new messages using AJAX request. By default admin-ajax.php is used as a backend script and this script has poor performance. However, it is the most compatible solution. If you want to reduce server load try to change "AJAX Engine" property to "Lightweight AJAX". It can be set on Settings -> Wise Chat Pro Settings page, select Advanced tab and then select "Lightweight AJAX" from the dropdown list. This option enables dedicated backend script that has a lot better performance. 

= How to customize CSS styles using "Custom CSS Styles" setting? =

All CSS classes related to the chat begins with "wc" prefix. Go to Settings -> Wise Chat Pro Settings, select Appearance tab, go to Advanced Customization section. Use "Custom CSS Styles" field to specifiy your own definitions of Wise Chat CSS classes. Valid CSS syntax is required. 

= How to backup messages posted in a channel? I would like to save all messages for offline reading. =

Go to Settings -> Wise Chat Pro Settings, select Channels tab and click "Backup" link next to the desired channel. All messages from the selected channel will be downloaded as a CSV file. You can open it using MS Excel, OpenOffice or any other editor that supports CSV format. 

= Are old messages being deleted from the chat window? =

By default - no, however, you can enable auto-cleaning messages feature. Go to Settings -> Wise Chat Pro Settings, select Channels tab. In the field "Auto-remove Messages" type number of minutes. Messages older than given amount of minutes will be auto-deleted. 

= How to limit the amount of users that are allowed to enter a channel? =

Go to Settings -> Wise Chat Pro Settings, select Channels tab. In the field "Users Limit" type number of users that allow to participate in a channel.  

= How to limit the amount of channels that an user can participate simultaneously? =

Go to Settings -> Wise Chat Pro Settings, select Channels tab. In the field "Channels Limit" type number of channels that an user is allowed to participate simultaneously.  

= How to reset username prefix (a number that is added to anonymous users name) anonymous users? =

Go to Settings -> Wise Chat Pro Settings, select General tab and click "Reset Username Prefix" button.  

= When I upload an animated GIF the thumbnail is not animated. What is wrong? =

WordPress has no support for resizing animated GIFs. 

= Why I can change background color of the chat window but I cannot change background color of the input field, buttons or borders? =

It is impossible to provide options to customize every aspect of user interface. Please use CSS styles to adjust styles to your needs.

= I have uploaded a picture with incorrect orientation and the plugin did not corrected it. What is wrong? =

Plugin detects EXIF data in images. If an image doesn't have this data then nothing will happen. If EXIF data indicates that the orientation is correct then also nothing will happen.

= I am experiencing errors like "Fatal error: Call to undefined function openssl_pkey_get_public() (...)". What is wrong? =

Make sure that openssl extension for your PHP installation is loaded and works correctly. In most cases theses errors are caused by missing openssl module.

= How to send messages using just keyboard in multiline mode? =

After you type a message use the key combination: Shift + ENTER

== Changelog ==

= 2.5.5 =
* Fixed: "You are not allowed to send private messages to this user." error

= 2.5.4 =
* Fixed: Invalid emoticons popup position in body's relative positioning

= 2.5.3 =
* Fixed: Cannot read property 'find' of null

= 2.5.2 =
* Fixed: HTML tags when editing messages
* Fixed: layout errors in FB mode
* Fixed: jQuery load() function depreciated warning

= 2.5.1 =
* Internal improvement of the settings page
* Fixed: 403 error when saving the settings (this was caused by Imunify360 rule #77142267)
* Fixed: not working users_list_hide_roles shortcode attribute
* Fixed: error "Notice: Trying to access array offset on value of type int in /wp-content/plugins/wise-chat-pro/src/WiseChatOptions.php"
* Fixed: PHP Warning: Cannot modify header information - headers already sent in /wp-content/plugins/wise-chat-pro/src/dao/user/WiseChatUserSettingsDAO.php
* Fixed: Notice: Undefined index: SERVER_ADDR

= 2.5 =
* Custom scrollbars
* Added option to choose username source field: Display Name, User Login, E-mail, First Name, Last Name, Nickname
* Added option to delete all messages and users at once
* Fixed: unauthorized access to private messages when switching between accounts
* Fixed: narrow window title in FB mode
* Fixed: Call to a member function setUser() on null

= 2.4.2 =
* Reply-to message option and message swipe gesture
* Fixed: sending wcUserSettings cookie only if necessary

= 2.4.1 =
* Added option to define an exclusive list of users who have access to the chat, shortcode example: [wise-chat access_users="{1,2,3}"]
* Fixed: "Call to a member function getId() on null" error when deleting / clearing channels

= 2.4 =
* Switched user session handling to cookies (remembering users for a long time)
* Performance optimization: reduced PHP session usage to minimum
* Performance optimization: reduced number of database queries
* Removed Powered By ad from BuddyPress chat's management page
* Added missing translations to BuddyPress chat's management page
* E-mail notifications - added option for limitless sending
* Fixed: rare empty messages when sending image /file attachments
* Fixed: responsive design issues in FB mode
* Fixed: post custom emoticons were blurry
* Fixed: Error: cURL error 28: Operation timed out after 10001 milliseconds with 0 bytes received
* Fixed: "Minimized By Default" option not working when "Force Username Selection" mode is enabled
* Fixed: CSV Injection

= 2.3.2 =
* Added hooks
* Channels backup feature: option to download private messages
* FB mode: minimize / maximize chat windows on title click
* Added a new variable to the info window template: {profileLinkInNewWindow}
* Offline users caching
* Fixed: &gt; / &lt; characters issues in the users list
* Fixed: no message when posting emojis (messages table converted from utf8 to utf8mb4)
* Fixed: error when posting a message: DOMDocument::loadHTML(): Empty string supplied as input
* Fixed: layout issues with percentage height of the chat (especially: 100%)
* Fixed: {% if allowToReceiveMessages %} errors caused by template processing class
* Fixed: error with deprecated session.php file

= 2.3.1 =
* Fixed: error with private messages permissions

= 2.3 =
* Private messaging permissions

= 2.2.3 =
* Fixed: recent chats indicator on mobile view

= 2.2.2 =
* Added missing translations

= 2.2.1 =
* E-mail notifications sent to chat users

= 2.2 =
* Upgrade of all themes
* Improved: Moved Javascript code to the footer

= 2.1.5 =
* Fixed: issue with chat button on own profile in BuddyPress integration

= 2.1.4 =
* Fixed: issues with aliases of custom emoticons

= 2.1.3 =
* Fixed: emoticons layer issues in the FB mode

= 2.1.2 =
* Option to add HTML tags to few messages in the Localization tab

= 2.1.1 =
* Fixed: offline users on BP groups

= 2.1 =
* Spam reporting button
* Option to adjust date and time format in messages
* Option to adjust lock time of user names
* Improved: W3C issues
* Improved: admin action buttons without direct img elements
* Fixed: security issue with the external links
* Fixed: Focus input field after sending the message
* Fixed: avatar images error when parsing HTML

= 2.0 =
* Moderation - edit messages option
* Option to edit own messages
* Users list search box
* Users list info windows (when mouse pointer enters username on the users list)
* Private chat button support
* Facebook API updated to v3.1
* Fixed: prevent loading all emoticons images if emoticon button is not enabled
* Fixed: switching between users when offline messages are enabled

= 1.9.2 =
* Fixed: "Argument #1 is not an array" warning in WiseChatRenderer.php file
* Fixed: "Undefined offset: 0" notice in WiseChatRenderer.php file

= 1.9.1 =
* Option to disable user names duplication check
* Option to disable private message confirmation dialog
* Fixed: Twitter authentication "Invalid oauth_verifier parameter" error in cases when multiple instances of the chat are present on the same page
* Fixed: error restoring private chats on multisite

= 1.9 =
* E-mail notifications
* Kicking users

= 1.8 =
* A new theme: "Air Flow"
* "Wise Chat Channel Users" widget for displaying current users
* Option to set username color for each user role separately
* Option to set limit to the length of user names
* Reloading page after sending valid POST forms on Force Username and Channel Password screens

= 1.7 =
* Offline users and offline private messages
* BuddyPress friends integration

= 1.6.2 =
* Option to disable chat channel window (Facebook-like mode)
* Option to display title bar above users list (Facebook-like mode)
* Option to minimize users list (Facebook-like mode)
* Option to start chat window and users list minimized (Facebook-like mode)

= 1.6.1 =
* Option to add bottom offset for Facebook-like mode
* Option to enable bottom offset in Facebook-like mode only for narrow screens

= 1.6 =
* Option to add custom emoticons
* Updated libraries for authentication through Facebook, Twitter and Google
* Option to output user roles as CSS classes to HTML source of the chat

= 1.5 =
* New engine "Ultra Lightweight AJAX" which consumes 5 times less CPU
* Set validity time on AJAX internal requests (preventing indexation by Web crawlers)
* Option to verify "X-Requested-With" header in AJAX requests
* Option to select which parts of a message are effected when user changes text color
* Option to exclude anonymous users from the counter calculation
* Option to generate a sound when user is mentioned using @UserName notation
* Option to display message and username in the same line
* Better displaying emoticons panel on mobile devices
* Added 20 new sounds to use in notifications
* Option to enable a sound notification when user joins or leaves the channel
* "User has joined/left the channel" notifications
* Shift+ENTER moves the cursor to the new line in multiline mode, ENTER sends the message
* Fixed: switched to JSON content type for AJAX requests (this may prevent from indexing raw data by Google)
* Fixed: jQuery up to version 3.1 is now supported
* Fixed: unwanted emoticons displayed after double quote
* Fixed: the plugin cannot be deleted in WordPress 4.6 and above
* Fixed: invalid encoding of UTF-8 characters in localized texts
* Fixed: unclickable image upload button on some themes

= 1.4.3 =
* Option to move users list down when Facebook-like mode is enabled
* Option to pass simple arrays in shortcode, array format: {element1, element2, ..., elementN} or {key1: element1, key2: element2, ..., keyN: elementN}
* Fixed: issue with broken menu on Wise Chat Pro settings page, this blocked navigation between settings sections
* Fixed: issues with setting background colors

= 1.4.2 =
* Option to enable / disable displaying errors to chat users

= 1.4.1 =
* Fixed: bug of multiple channels on the same page

= 1.4 =
* Sidebar mode - displaying chat attached to the right side of the window

= 1.3.2 =
* Fixed: errors with sending images when there is no Exif PHP module installed

= 1.3.1 =
* Pending messages features - manual approval of messages
* [wise-chat-channel-export] shortcode displaying a button that allows to export all messages from a channel to CSV file
* write-only mode option (available only in shortcode)
* Fixed: bug of integration with BuddyPress 2.6 and greater

= 1.3 =
* Emoticons layer expands to the top
* Three new emoticon sets
* Shift+ENTER moves to the new line in message input field (when multiline mode is not enabled)
* Allow to use wide range of letters (Unicode characters) in username
* Option to select which user roles have to be hidden in the users list
* Debug mode option (easier to report errors)
* Better navigation between private messages on narrow devices
* Better Clear theme appearance on narrow devices
* User list toggle button on narrow devices
* Fixed: /whois command does not accept usernames with spaces, now such names can be typed in double quotes

= 1.2.2 =
* Fixed: Charset issues on button labels

= 1.2.1 =
* Fixed: Usernames Mode option stopped working

= 1.2 =
* Flexible controls on narrow screens
* Fixed: missing vertical scrollbar on the users list
* Fixed: disappearing private conversations after page refresh
* Major code refactoring (client side)

= 1.1.1 =
* BuddyPress integration - translations for labels on group's Manage page
* BuddyPress integration - safety warning for disabled groups support

= 1.1 =
* BuddyPress integration
* Clickable usernames on the users list
* Additional filter that allows to filter outgoing links
* Option to give access to the chat for specific user roles only
* Option to hide anonymous users on the users list
* Option to make the chat read-only for selected user roles
* Option to give ban and/or delete permissions to each user role separately
* Messages timestamp split to two spans - date and time
* Improved safety during deletion of images uploaded by the chat
* Option to auto-hide users list on the narrow screens
* Fixed: word wrapping of long messages
* Fixed: disappearing avatars

= 1.0.2 =
* Fixed: images upload error on multisite

= 1.0.1 =

* Fixed: logged in user displayed as an anonymous user
* Fixed: bugs during installation on some systems

= 1.0 =

Initial version