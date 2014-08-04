# Project 4 - Final Project
****************************
- Glen Barger
- CSCI S-15
- Due: 2015-08-07

## Project Links
****************
- My [Github Repo](https://github.com/gbarger/dwa15-p4)
- My [Live URL](https://gbarger.com/school/2014/summer/csci_s15/p4/public). You can log in with a username AND password of test@test.com

## Project Description
***********************
This project is an attempt to create an online web music player. You can upload mp3s that are automatically added to a music library. You can create playlists and add music from your library to the playlists, then play the songs with the built in player.

## Libraries
************
The project uses a number of libraries to build functionality
- The PHP framework [Laravel](http://laravel.com/) is used as the framework to build the application.
- [jQuery](http://jquery.com/) from the [Google CDN](https://developers.google.com/speed/libraries/devguide#jquery) is used as a core to handle most of the JavaScript/AJAX events for the application.
- [Dropzone](http://www.dropzonejs.com/), is used as the browser side of the drag and drop upload to send the mp3s to the server.
- The id3 tags are read from the mp3s using [nass600's getID3 package](https://packagist.org/packages/nass600/get-id3)
- The music player is [jPlayer](http://jplayer.org/), a JavaScript/Flash player used to play the music on the site
- The table sorting is handled by the [Table Sorter plugin](http://tablesorter.com/)
- The favicon was generated at [favicon.cc](http://favicon.cc) using the icon I created for the music library
- The music was all taken from legally free sources from [Brad Sucks](http://www.bradsucks.net/) for his music, and the remainder of the music from the [Creative Commons Free Music Archive](http://freemusicarchive.org/curator/creative_commons/)
- I created the 16th note, +, and trash icons. The remainder of the icons were taken from the included packages
- General styling inspiration is from an old version if iTunes

## Notes and Comments
**********************
I would consider this a beta product. There's a lot I would change, or redo given a greater timeline.
- Create better support for the player and how the songs are loaded and started. The player is a litte buggy, even in the examples on their site, so I would ned to spend some time debugging the jPlayer code.
- Add inline edit support to update the tags from the playlists. The id for the songs table isn't in the playlist items table so updates on the javascript and controller would need to be made to support this.
- Add the ability to drag and reorder the items within the playlist.
- Refactor my JavaScript to make it more modular and easier to read.
- Utilize more Laravel security and validation features. I built my existing manual validation before we covered validation in class, and didn't take the time to figure out where I need to refactor my code to use the built in validation. I would also like to utilize more security on my posts with custom filters to ensure that owners can only update their items as well as access their files. I wouldn't want someone getting access to files they don't own.
- I would also like to have a better design, unfortunately that isn't a strong point for me.