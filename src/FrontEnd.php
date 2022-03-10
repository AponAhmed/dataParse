<?php

namespace dataParse\src;

/**
 * Description of FrontEnd
 *
 * @author Mahabub
 */
class FrontEnd {

    //put your code here
    public static function init() {
        self::html();
    }

    private static function html() {
        ?>
        <!doctype html>
        <html lang="en">
            <head>
                <!-- Required meta tags -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">

                <!-- Bootstrap CSS -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
                <link rel="stylesheet" href="./assets/custom-style.css">
                <title>Data Parser</title>
            </head>
            <body>
                <nav class="navbar navbar-light bg-light">
                    <div class="container">
                        <a class="navbar-brand" href="#">
                            <img style="max-width: 50px;padding: 8px;border-radius: 50px;background: #eaeaeade;" src="./assets/logo.png" alt="Siatex">
                        </a>
                    </div>
                </nav>
                <div class="wrap">
                    <div class="container">
                        <div class="row">
                            <div class='col-md-3'>
                                <div class="control-wrap">
                                    <div class='control'>
                                        <button type="button" id="startBtn" class="btn btn-primary btn-sm" onclick="StartParse(event)">Start</button>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="resetParser(event)">Reset</button>
                                        <a  href="./storage/res.csv" class="downloadBtn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Download Data with CSV File</title><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M176 262.62L256 342l80-79.38M256 330.97V170"/><path d="M256 64C150 64 64 150 64 256s86 192 192 192 192-86 192-192S362 64 256 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                                        </a>

                                    </div>
                                    <div class="info-wrap">
                                        <label id="counter"><span class='prog'></span><span id="completeLink"></span>/<span id="totalLinks"></span></label>
                                    </div>
                                </div>
                                <!--<button onclick="singleExe()" type="button">Exe</button>-->
                                <textarea id="UrlInput" class="form-control" placeholder="Put URLs here"></textarea>
                            </div>
                            <div class='col-md-9'>
                                <table class="table table-striped table-small">
                                    <thead>
                                        <tr>
                                            <th scope="col">Url</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">H1</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Keyword</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resData"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
                <script src="./assets/script.js"></script>
                <!-- Optional JavaScript; choose one of the two! -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

                <!-- Option 2: Separate Popper and Bootstrap JS -->
                <!--
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
                -->
            </body>
        </html>
        <?php
    }

}
