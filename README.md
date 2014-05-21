#Finder View

##About
* File Viewer using PHP Language.
* Support Hierarchical View

##Dependaries
All of libraries is included in `libs`. used libraries is below.
* jQuery 2.1.1 `./libs/jquery`
* Bootstrap 3.1.1 `./libs/bootstrap-3.1.1`

##Set Up
* Upload all of this `repo.` to your server directory that you want to apply File Viewer.
* To apply exceptions that you don'y want to display, add exceptional file name at `./api.php`, line 2 `$exclude = array(...`.

##API
* `./api.php` : json data, return file & folder list data.
* GET / Parameters
   * callback : jsonp
   * mode :
     * '0' : hierarchical data
     * '1' : single folder data
   * folder : base64 encoded uri, if this parameter is not exists, return `./` data.

##Sample
* http://moblab.co.kr/seminar
