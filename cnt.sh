#!/bin/bash
#Name of user to check
#  If you have multiple usernames, separate them with a space
#  The full name is not required, just enough to not be ambiguous
USERS="NasoohOlabi Moutaz"
#Directories
SOURCE=.

for USER in $USERS
do
    #clear blame files
    echo "" > $USER-Blame.php
    echo "" > $USER-Blame.css
    echo "" > $USER-Blame.js
    echo "Finding blame for $USER..."
    #Php files
    echo "  Finding blame for Php files..."
    for i in $(find . -type f -name "*.php")
    do
 
        git blame "$i" | grep "$USER" | cut -c 70- >> "$USER-Blame.php"
    done
    #Header files
    echo "  Finding blame for css files..."
    for i in $(find . -type f  -name "*.css")
    do
        git blame "$i" | grep "$USER" | cut -c 70- >> "$USER-Blame.css"
    done
    #Shell script files
    echo "  Finding blame for js files..."
    for i in $(find . -type f  -name "*.js")
    do
 
        git blame "$i" | grep "$USER" | cut -c 70- >> "$USER-Blame.js"
    done
done

for USER in $USERS
do
#cloc
echo "Blame for all users found! Cloc-ing $USER..."
cloc $USER-Blame.* --quiet
#this line is for cleaning up the temporary files
#if you want to save them for future reference, comment this out.
rm $USER-Blame.* -f
done