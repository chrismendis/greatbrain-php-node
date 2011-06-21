<?php

# ============================================================================ #

/**
 *  greatbrain-php-node
 * 
 *  A template for making nodes for The Great Brain using PHP.
 *  Makes use of the Sinatra-like PHP micro-framework, Limonade.
 * 
 *  Code repository: {@link https://github.com/chrismendis/greatbrain-php-node}
 *  
 *  @author Chris Mendis
 *  @copyright Copyright (c) 2011 Chris Mendis
 *  @license http://opensource.org/licenses/mit-license.php The MIT License
 */

#   -----------------------------------------------------------------------    #
#    Copyright (c) 2011 Chris Mendis                                           #
#                                                                              #
#    Permission is hereby granted, free of charge, to any person               #
#    obtaining a copy of this software and associated documentation            #
#    files (the "Software"), to deal in the Software without                   #
#    restriction, including without limitation the rights to use,              #
#    copy, modify, merge, publish, distribute, sublicense, and/or sell         #
#    copies of the Software, and to permit persons to whom the                 #
#    Software is furnished to do so, subject to the following                  #
#    conditions:                                                               #
#                                                                              #
#    The above copyright notice and this permission notice shall be            #
#    included in all copies or substantial portions of the Software.           #
#                                                                              #
#    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,           #
#    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES           #
#    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND                  #
#    NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT               #
#    HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,              #
#    WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING              #
#    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR             #
#    OTHER DEALINGS IN THE SOFTWARE.                                           #
# ============================================================================ # 

require_once 'lib/limonade.php';

function configure() {
  option('public_dir', option('root_dir').'/public/');
  option('public_path', option('base_path').'public/');
  $isHTTPS = empty($_SERVER['HTTPS']) == false;
  $isHTTPS ? option('host_name', 'https://' . $_SERVER['HTTP_HOST']) : option('host_name', 'http://' . $_SERVER['HTTP_HOST']);
}

/**
 *  Define the /generate route, as per the Great Brain documentation:
 *  1. one API call at "/generate?callback=?" that returns in the following JSON response:
 *    {
 *      "result": "<img src='http://myawesomenode.com/images/somethingcrazy.gif' />"
 *    }
 */
dispatch('/generate', 'generate');
  function generate() {

    // You get access to the 'ping state' from the Great Brain, through the "ping" URL param.
    // Currently, as per the Great Brain docs, the 'ping state' is either 0 or 1.
    // We are just going to ignore the 'ping state' in this sample node.
    // $pingState = $_GET['ping'];

    // The Great Brain uses jQuery to make a JSONP call to its nodes.
    // As such, we can get the JSONP callback function's name through the "callback"
    // URL paramater.
    $callback = $_GET['callback'];

    // Construct the path to the directory where all our Great Brain images live
    $imagesDirectory = option("public_dir") . "/img/";

    // Get a directory listing of the images directory, just like using `ls`
    $images = scandir($imagesDirectory, 1);
    $images = array_slice($images, 0, sizeof($images) - 2);
    $image = "<img src='" . option("host_name") . option("public_path") . "img/" . $images[array_rand($images)] . "' />";
    return js($callback . '({ "result": "' . $image . '" })');
  }

/**
 *  Define the /pong route, as per the Great Brain documentation:
 *  2. one API call at "/pong?callback=?" that returns JSON as either 0 or 1. Randomization of these
 *  two values is ideal, but it is up to you to decide how you want to determine the response value.
 *  The format should be as follows:
 *    {
 *      "result": 0
 *    }
 */
dispatch('/pong', 'pong');
  function pong() {

    // The Great Brain uses jQuery to make a JSONP call to its nodes.
    // As such, we can get the JSONP callback function's name through the "callback"
    // URL paramater.
    $callback = $_GET['callback'];

    // Generate a random binary value
    $randomNumber = rand(0, getrandmax());
    $pong = $randomNumber % 2;

    return js($callback . '({ "result": ' . $pong . ' })');
  }

// Run the application
run();