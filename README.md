XUIWeb
======

A web UI framework. 

Build
----------------------
- Install php and config php cli. 

 [See here. ](http://www.php.net/downloads.php)
 [And here. ](http://www.php.net/manual/en/features.commandline.introduction.php)

- Install node.js. 

 [See here. ](http://nodejs.org/)  

- Install uglifycss. 

 <code>npm install -g uglifycss</code>

- Install uglifyjs. 

 <code>npm install -g uglify-js</code>

- Install less. 

 <code>npm install -g less</code>

- Build. 

 <code>php build.php</code>


Structure
--------------------------

*build.php* generates XUI.js, XUI.min.js into *release/js* and XUI.css, XUI.min.css and related images 
for each skin into *release/css/<skin-name>*.

*source* is where build.php gets codes to be built.

To build XUI.js, XUI.min.js, *build.php* combines each file in *source/js/base* (which contains basic js files) 
and *source/js/controls* (which contains controls) and makes some necessary optimazation.

To build XUI.css, XUI.min.css, *build.php* first looks into *source/css/skin* to find out that how many skin folders
are standing there. And for each skin folder(except the *default* folder, which contains default skin files), 
*build.php* generates a seperate XUI.js, XUI.min.js into *release/css/<skin-name>*. *build.php* completes that task by 
combining each file in *source/css/base*, *source/css/control*, *source/css/skin/default*, *source/css/skin/<skin-name>*,
compiling less codes to CSS codes, and finally mask some necessary optimazation.

The order in while files are combined is exactly the order in which they are listed above. Files in the same folder
will be combined according to their order-mark of their filename (ie, no.XXXX.css or no.XXXX.js). Note that files in 
*source/js/controls* and *source/css/control* are not combined in order. 

FontV2 structure
-----------------------------

* *FontV2/path.jsp*, used to get the ralative path of a JSP file to support page package.
* *FontV2/XUI*, compiled XUI files.
* *FontV2/page*, page packages.


**See *FontV2/page/index/js/switch_page.js* to get a full understanding of compatible page loading mechanism.**

