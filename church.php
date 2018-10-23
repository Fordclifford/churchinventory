<?php
//user.php
include './config.php';
include('database_connection.php');

if (!isset($_SESSION["type"])) {
    header('location:login.php');
}

if ($_SESSION["type"] != 'master') {
    header("location:index.php");
}

include('header.php');
?>
<span id="alert_action"></span>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                        <h3 class="panel-title">Church Branches List</h3>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                        <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-success btn-xs">Add</button>
                    </div>
                </div>

                <div class="clear:both"></div>
            </div>
            <div class="panel-body">
                <div class="row"><div class="col-sm-12 table-responsive">
                        <table id="user_data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                   <th>User</th>
                                    <th>Date Added</th>
                                     <th>Status</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="userModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="user_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Church</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Enter Church Name</label>
                            <input type="text" name="name" id="name" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Enter Location </label>
                            <input type="text" name="location" id="location" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Select User</label>
                            <select title=" Choose User"  style=" height: 40px" class="form-control w3-round-large" name="user_id" id="user_id"
                                    value="<?php echo $yr; ?>">
                                <option value=''>------- Select User --------</option>
                                <?php
                                $sql = "SELECT * FROM user_details";
                                $resu = mysql_query($sql);
                                if (mysql_num_rows($resu) > 0) {
                                    while ($row = mysql_fetch_object($resu)) {
                                        echo "<option value='" . $row->user_id . "'>" . $row->user_name . "</option>";
                                    }
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" id="id" />
                        <input type="hidden" name="btn_action" id="btn_action" />
                        <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <script>
        $(document).ready(function () {

            $('#add_button').click(function () {
                $('#user_form')[0].reset();
                $('.modal-title').html("<i class='fa fa-plus'></i> Add Church");
                $('#action').val("Add");
                $('#btn_action').val("Add");
            });

            var userdataTable = $('#user_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "church_fetch.php",
                    type: "POST"
                },                
                "columnDefs": [
                    {
                        "target": [7, 8],
                        "orderable": false
                    }
                ],
                "pageLength": 25
            });

            $(document).on('submit', '#user_form', function (event) {
                event.preventDefault();
                $('#action').attr('disabled', 'disabled');
                var form_data = $(this).serialize();
                $.ajax({
                    url: "church_action.php",
                    method: "POST",
                    data: form_data,
                    success: function (data)
                    {
                        $('#user_form')[0].reset();
                        $('#userModal').modal('hide');
                        $('#alert_action').fadeIn().html('<div class="alert alert-success">' + data + '</div>');
                        $('#action').attr('disabled', false);
                        userdataTable.ajax.reload();
                    }
                })
            });

            $(document).on('click', '.update', function () {
                var id = $(this).attr("id");
                var btn_action = 'fetch_single';
                $.ajax({
                    url: "church_action.php",
                    method: "POST",
                    data: {id: id, btn_action: btn_action},
                    dataType: "json",
                    success: function (data)
                    {
                        $('#userModal').modal('show');
                        $('#location').val(data.location);
                        $('#name').val(data.name);
                        $('#user_id').val(data.user_id);
                        $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Church");
                        $('#id').val(id);
                        $('#action').val('Edit');
                        $('#btn_action').val('Edit');
                       
                    }
                })
            });

            $(document).on('click', '.delete', function () {
                var id = $(this).attr("id");
                var status = $(this).data('status');
                var btn_action = "delete";
                if (confirm("Are you sure you want to change status?"))
                {
                    $.ajax({
                        url: "church_action.php",
                        method: "POST",
                        data: {id: id, status: status, btn_action: btn_action},
                        success: function (data)
                        {
                            $('#alert_action').fadeIn().html('<div class="alert alert-info">' + data + '</div>');
                            userdataTable.ajax.reload();
                        }
                    })
                } else
                {
                    return false;
                }
            });

        });
    </script>

    <?php
    include('footer.php');
    ?>
