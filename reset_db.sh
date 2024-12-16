#!/bin/bash

DB_USER="root" # Replace with your MySQL username if different
DB_PASS="1234"
DB_HOST="127.0.0.1"
DB_PORT="3307"
SQL_FILE_PATH="/Applications/XAMPP/xamppfiles/htdocs/sql_reset.sql"
MYSQL_PATH="/Applications/XAMPP/xamppfiles/bin/mysql"

if [ ! -f "$SQL_FILE_PATH" ]; then
    echo "SQL file not found: $SQL_FILE_PATH"
    exit 1
fi

COMMAND="$MYSQL_PATH --user=\"$DB_USER\" --password=\"$DB_PASS\" --host=\"$DB_HOST\" --port=\"$DB_PORT\" < \"$SQL_FILE_PATH\""
echo "Executing: $COMMAND"

eval $COMMAND

if [ $? -eq 0 ]; then
    echo "SQL script executed successfully."
else
    echo "Failed to execute SQL script."
    exit 1
fi

