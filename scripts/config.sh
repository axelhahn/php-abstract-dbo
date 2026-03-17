# ----------------------------------------------------------------------
# CONFIG
# includet by generate_classdoc.sh
# standing in its directory
# ----------------------------------------------------------------------

# go to approot to reference local input and ouput files
cd ..
APPDIR="$( pwd )"
OUTDIR="$APPDIR/docs/70_Classes"

FILELIST="
    src/pdo-db.class.php
    src/pdo-db-base.class.php
    src/pdo-db-attachments.class.php
"

# web url to watch sources
# The relative filename to approot will be added + "#L" + line number
# (which works for Github and Gitlab for sure)
SOURCEURL="https://github.com/axelhahn/php-abstract-dbo/blob/main"

# relative or absolute path of local php doc parser
PARSERDIR="../php-classdoc"

# ----------------------------------------------------------------------
