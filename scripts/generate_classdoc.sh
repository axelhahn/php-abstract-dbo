#!/bin/bash
# ====================================================================== 
#
# Generator for markdown files from php class files
#
# ----------------------------------------------------------------------
# 2025-07-21  v1.0  axelhahn  initial version
# ====================================================================== 

echo "

#####|  Generate class documentation  |#####

"
cd "$( dirname "$0" )" || exit 1
. "config.sh" || exit 2
cd "$PARSERDIR" || exit 3

for phpclassfile in $FILELIST
do
    echo "----------------------------------------------------------------------"
    echo "processing: $phpclassfile"
    ./parse-class.php \
        --debug \
        --source "$SOURCEURL/$phpclassfile" \
        --out md \
        "$APPDIR/$phpclassfile" > "$OUTDIR/$(basename $phpclassfile).md"
done
echo "----------------------------------------------------------------------"

echo "Done".

# ----------------------------------------------------------------------
