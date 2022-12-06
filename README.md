# Find and move (or copy) pictures and videos by resolution



## Presentation

Find and move pictures and videos filtered by resolution recursivly. It takes a directory, and move (or copy) all pictures matching a specific list of resolutions (portrait and landscape are ignored) to the exact same arbo in another directory.

## Requirement

 - Unix system
 - php (7.1+)
 - ffmpeg

## Installation

 - Clone this repository on your computer 

## Params

- Input directory
- Ouput directory
- Resolutions list, coma separated : 1234`x`1234`,`1234`x`1234
- `copy` or `move` flag

## Usage
```
cd [PATH-TO]/find-and-move-pictures-and-videos-by-resolution/
php find-and-move-pictures-and-video-by-resolution.php "/my/photo/dir/"  "/my/filtered/photo/dir/" 4032x1960,2224x1080,1440x1440,3024x3024 copy
```
In this example photos and videos in `/my/filtered/photo/dir/example/subdir/` matching one of the resolution are copied to `/my/filtered/photo/dir/example/subdir/`