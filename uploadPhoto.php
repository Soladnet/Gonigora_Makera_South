<?php
if (!isset($_SESSION['auth'])) {
    session_unset();
    header("Location:index.php");
}
?>
<!DOCTYPE HTML>

<html lang="en">
    <head>
        <?php
        include 'fileuploadheader.php';
        ?>
        <link rel="stylesheet" media="screen and (min-device-width: 1024px)" href="css/main.css" />
        <script>
            $(document).ready(function(){
                getAlbum('<?php echo $_SESSION['auth']['id'] ?>', "album");
            });
            
        </script>
    </head>
    <body>
        <?php include_once("nav.php") ?>
        <div class="inner-container" >
            <?php include_once("left.php"); ?>
            <div class="container">
                <span class="gossbox-list">
                    <div class="well">
                        <h3 >Upload Notes</h3>
                        <ul >
                            <li>The maximum file size for uploads is <strong>2 MB</strong>.</li>
                            <li>Only image files (<strong>JPG, GIF, PNG</strong>).</li>
                            <li>You can <strong>drag &amp; drop</strong> files from your desktop on this webpage if your browser is Google Chrome, Mozilla Firefox and Apple Safari.</li>
                        </ul><div class="clear"></div>

                    </div>
                </span>
                <!--            The file upload form used as target for the file upload widget -->
                <form id="fileupload" action="upload/?img=" method="POST" enctype="multipart/form-data">
                    <span class="gossbox-list">
                        <div class="well">
                            <h3 >Select Album or Create a new one</h3>
                            <span id="album"></span>
                            <div class="clear"></div>
                        </div>
                    </span>
                    <!--                The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                    <div class="row fileupload-buttonbar">
                        <div class="span7">
                            <!--                         The fileinput-button span is used to style the file input field as button -->
                            <span class="btn btn-success fileinput-button">
                                <span class="ui-icon ui-icon-plusthick" style="float: left;"></span>
                                <span>Add to album</span>
                                <input type="file" name="files[]" multiple>
                            </span>
                            <button type="submit" class="btn btn-primary start">
                                <span class="ui-icon ui-icon-arrowthick-1-n" style="float: left;"></span>
                                <span>Start upload</span>
                            </button>
                            <button type="reset" class="btn btn-warning cancel">
                                <span class="ui-icon ui-icon-cancel" style="float: left;"></span>
                                <span>Cancel upload</span>
                            </button>
                            <!--                        <button type="button" class="btn btn-danger delete">
                                                        <span class="ui-icon ui-icon-trash" style="float: left;"></span>
                                                        <span>Delete</span>
                                                    </button>
                                                    <input type="checkbox" class="toggle">-->
                        </div>
                        <!--                     The global progress information -->
                        <div class="span5 fileupload-progress fade">
                            <!--                         The global progress bar -->
                            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <div class="bar" style="width:0%;"></div>
                            </div>
                            <!--                         The extended global progress information -->
                            <div class="progress-extended">&nbsp;</div>
                        </div>

                    </div>
                    <!--                 The loading indicator is shown during file processing -->
                    <div class="fileupload-loading"></div>
                    <br>
                    <!--                 The table listing the files available for upload/download -->
                    <table role="presentation" class="table table-striped">
                        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">
                        </tbody>
                    </table>

                </form>
                <div id="photos" >
                    <ul class="box">
                        <span id="albumImages">
                        </span>
                    </ul>
                </div>
            </div>
            <div id="dialog"></div>
            <?php include_once("right.php"); ?>
        </div>

    </body>
</html>

