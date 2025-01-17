#!/bin/bash

DB_USER="root" # Replace with your MySQL username if different
DB_HOST="localhost"
DB_PORT="3306"
SQL_FILE_PATH="/Applications/XAMPP/xamppfiles/htdocs/sql_reset.sql"
MYSQL_PATH="/Applications/XAMPP/xamppfiles/bin/mysql"

if [ ! -f "$SQL_FILE_PATH" ]; then
    echo "SQL file not found: $SQL_FILE_PATH"
    exit 1
fi

COMMAND="$MYSQL_PATH --user=\"$DB_USER\" --host=\"$DB_HOST\" --port=\"$DB_PORT\" < \"$SQL_FILE_PATH\""
# echo "Executing: $COMMAND"

eval $COMMAND

if [ $? -eq 0 ]; then
    echo "SQL script executed successfully."
else
    echo "Failed to execute SQL script."
    exit 1
fi

