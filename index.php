<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TIN TUC</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css" />
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <div class="container mt-5">
        <div class="row mb-2">
            <div class="col-md-6 col-12 mt-2">
                <button type="button" class="btn btn-primary add">Add post</button>
            </div>
            <div class="col-md-6 col-12 mt-2 text-md-right">
                <button type="button" class="btn btn-primary import">Import Data</button>
                <button type="button" class="btn btn-primary export">Export Data</button>
                <form id="importFile" enctype="multipart/form-data" style="display: none;">
                    <div class="input-group text-left mt-2 hidden import-file">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="inputGroupFile02" name="importFile">
                            <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <button type="submit" class="btn input-group-text cursor-pointer" id="importData">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <table id="post" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
            <thead>
                <tr>
                    <th class="text-capitalize text-center">id</th>
                    <th class="text-capitalize text-center">title</th>
                    <th class="text-capitalize text-center">content</th>
                    <th class="text-capitalize text-center">edit/delete</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id=modalForm>
                        <input type="hidden" name='id'>
                        <div class="form-group">
                            <label for="title" class="col-form-label">Title:</label>
                            <input type="text" class="form-control" id="title" name='title'>
                        </div>
                        <div class="form-group">
                            <label for="content" class="col-form-label">Content:</label>
                            <textarea class="form-control" id="content" name='content'></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" form='modalForm' class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
    <script>
        const url = "<?php echo $domain; ?>";
    </script>
    <script src="<?php echo $domain; ?>/js/main.js"></script>
</body>

</html>