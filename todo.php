<?php

// Create array to hold list of todo items
$items = array();

// List array items formatted for CLI
function list_items($list)
{
    $result = '';
    foreach ($list as $key => $value)
    {
//Return string of list items separated by newlines.
        $result .= "[" . ($key + 1) . "] TODO $value" . PHP_EOL;
    }
    return $result;
}

//****************************************************************************
// Get STDIN, strip whitespace and newlines,
// and convert to uppercase if $upper is true
function get_input($upper = false)
{
    $result = trim(fgets(STDIN));
    return $upper ? strtoupper($result) : $result;
}

//****************************************************************************
//Sort menu
function sort_menu($items)
{
    echo '(A) - Z, (Z) - A, (O)rder Entered, (R)everse order entered: ';
    switch(get_input(TRUE))
    {
        case 'A':
            asort($items);
            break;
        case 'Z':
            arsort($items);
            break;
        case 'O':
            ksort($items);
            break;
        case 'R':
            krsort($items);
            break;
    }
    return $items;
}

//****************************************************************************
//Gets list items from a .txt file
function get_items($file, $array)
{
    $handle = fopen($file, 'r');
    $contents = trim(fread($handle, filesize($file)));
    $list = explode("\n", $contents);
        foreach ($list as $value)
        {
            array_push($array, $value);
        }
    fclose($handle);
    return $array;
}

//****************************************************************************
//Saves todo list items to a new file
function newfile($new_file, $array)
{
    $handle = fopen($new_file, 'w');
        foreach ($array as $value)
        {
        fwrite($handle, $value . PHP_EOL);
        }
    fclose($handle);
}

//****************************************************************************
// The loop!
do
{
    // Echo the list produced by the function
    echo list_items($items);
    // Show the menu options
    echo '(N)ew item, (S)ort (R)emove item, (O)pen File, s(A)ve, (Q)uit : ';

    // Get the input from user
    // Use trim() to remove whitespace and newlines
    $input = get_input(TRUE);

//****************************************************************************
//Hidden commands for 'power users' removes list items from either the beginning or end
    if ($input == 'F')
    {
        array_shift($items);
    }
    elseif ($input == 'L')
    {
        $last = array_pop($items);
    }

    // Check for actionable input
    if ($input == 'N')
    {
        // Ask for entry
        echo 'Enter item: ';
        $item = get_input();
        echo 'Add to (B)eginning or (E)nd of the list? ';
        $input = get_input(TRUE);
        if ($input == 'B')
        {
        // Add entry to list array
            array_unshift($items, $item);
        }
        else
        {
            $items[] = $item;
        }
    }
    elseif ($input == 'S')
    {
        $items = sort_menu($items);
    }
    elseif ($input == 'R')
    {
        // Remove which item?
        echo 'Enter item number to remove: ';
        // Get array key
        $key = get_input();
        // Remove from array
        unset($items[$key - 1]);
    }

//****************************************************************************
//Allows the user to open a file and import the information into todo list
    elseif ($input == 'O')
    {
        echo 'Open File: ';
        $file = get_input();
        if(is_readable($file)){
        $items = get_items($file, $items);
        }
        else
        {
            echo "This is not a valid file name.\n";
        }
    }

//****************************************************************************
//Allows user to save a todo list
    elseif ($input == 'A')
    {
        echo 'Save file to: ';
        $new_file = get_input();
        if(file_exists($new_file))
        {
            echo "This file already exists. Saving will overwrite it. Are you sure you would like to save?\n";
            echo "(Y)es or (N)o: ";
            $input = get_input(TRUE);
            if($input == 'Y')
            {
                newfile($new_file, $items);
                echo "Save successful!\n";
            }
            else
            {
                echo "File not saved. Sorry.\n";
            }
        }
        else
        {
            newfile($new_file, $items);
            echo "Save successful!\n";
        }
    }

//****************************************************************************
// Exit when input is (Q)uit
}
while ($input != 'Q');

// Say Goodbye!
echo "Goodbye!\n";
// Exit with 0 errors
exit(0);
