<?php
require 'includes/header.inc.php';

if(isset($_SESSION['admin_error'])) {
    echo'  <div class="error-container">';
      echoError($_SESSION['admin_error']);
      unset($_SESSION['admin_error']);
    echo'</div>';
  } elseif(isset($_SESSION['admin_success'])) {
      if ($_SESSION['admin_success'] == 'Registration successful') {
        echo'  <div class="error-container">';
          validRegisterAl();
          echo'</div>';
          
      } else {
        echo'  <div class="error-container">';
          echoSuccess($_SESSION['admin_success']);
          echo'</div>';
      }
      unset($_SESSION['admin_success']);
  }

// check if table is set
if (isset($_GET['table']) && !empty($_GET['table'])) {
    $table = $_GET['table'];
    $db_conn = db_connect();

    $result_table = executeQuery($db_conn, "SHOW TABLES LIKE '$table'");
    if ($result_table->num_rows === 0) {
        header('Location: index.php');
        exit;
    }
    
} else {
    header('Location: index.php');
    exit;
}




// get table columns
$result = executeQuery($db_conn, "SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND TABLE_SCHEMA = '" . $arrConfig['connect_DB'][3] . "'");

$columns = [];
$column_types = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['COLUMN_NAME'];
    $column_types[$row['COLUMN_NAME']] = strtoupper($row['COLUMN_TYPE']);

    if (str_contains($column_types[$row['COLUMN_NAME']], 'VARCHAR') && $column_types[$row['COLUMN_NAME']] !== 'VARCHAR(7)') {
        $column_types[$row['COLUMN_NAME']] = 'VARCHAR';
    }
}

if ($table == "users") {
    $result = executeQuery($db_conn, "SELECT * FROM $table WHERE id_users NOT IN (" . $_SESSION['admin_id'] . ", 8)");
} else {
    $result = executeQuery($db_conn, "SELECT * FROM $table");
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Determine the ID column name based on the table name
$idColumnName = 'id_' . $table; // Default format
if ($table === 'posts') {
    $idColumnName = 'post_id'; // Special case for posts table
}



mysqli_close($db_conn);

if(isset($_SESSION['error'])) {
    echo'  <div class="error-container">';
      echoError($_SESSION['error']);
      unset($_SESSION['error']);
    echo'</div>';
  } elseif(isset($_SESSION['success'])) {
      if ($_SESSION['success'] == 'Registration successful') {
        echo'  <div class="error-container">';
          validRegisterAl();
          echo'</div>';
          
      } else {
        echo'  <div class="error-container">';
          echoSuccess($_SESSION['success']);
          echo'</div>';
      }
      unset($_SESSION['success']);
  }

?>

<div class="w-screen p-2 flex items-center justify-center">
    <div class="card w-[95%] min-h-[90vh] bg-base-100 shadow-xl relative">
        <div class="card-body max-h-[80vh]">
            <div class="overflow-x-auto">
                <table class="table" id="table-View">
                    <!-- head -->
                    <thead>
                        <tr>
                            <?php
                            foreach ($columns as $column) {
                                echo '<th class="hover">' . $column . '</th>';
                            }
                            // Check if actions column should be displayed
                            if ($tablePermissions[$table]['editable'] || $tablePermissions[$table]['deletable']) {
                                echo '<th>Ações</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Use $idColumnName in the edit and delete links
                            foreach ($data as $row) {
                                echo '<tr class="hover">';
                                foreach ($columns as $column) {
                                    if ($column == 'post_url') {
                                        // Assuming 'post_url' column contains the file name
                                        $fileSrc = $arrConfig['url_posts'] . '/' . $row['post_type'] . '/' . $row[$column]; // Adjust the path as needed
                                        // Determine the file type based on the 'type' column or file extension
                                        if ($row['post_type'] == 'image') {
                                            echo '<td><img src="' . $fileSrc . '" alt="Post Image" style="max-height: 100px;"></td>'; // Set max-height as per requirement
                                        } elseif ($row['post_type'] == 'video') {
                                            echo '<td><video controls style="max-height: 100px;"><source src="' . $fileSrc . '" type="video/mp4">Your browser does not support the video tag.</video></td>';
                                        } elseif ($row['post_type'] == 'audio') {
                                            echo '<td><audio controls><source src="' . $arrConfig['url_posts']. 'audio/'.$row['post_url'].'" type="audio/mpeg">Your browser does not support the audio element.</audio></td>';
                                        } else {
                                            // Fallback for unknown types
                                            echo '<td>Unsupported file type</td>';
                                        }
                                    } else {
                                        echo '<td>' . $row[$column] . '</td>';
                                    }
                                }
                                // Check permissions for edit and delete actions
                                if ($tablePermissions[$table]['editable'] || $tablePermissions[$table]['deletable']) {
                                    echo '<td class="flex justify-end gap-2 m-auto">';
                                    if ($tablePermissions[$table]['editable']) {
                                        echo '<a href="tableEdit.php?table=' . $table . '&id=' . $row[$idColumnName] . '" class="btn btn-sm"><i class="fi fi-br-blog-pencil"></i></a>';
                                    }
                                    if ($tablePermissions[$table]['deletable']) {
                                        // Echo the link with an onclick event that calls customConfirm()
                                        echo '<a href="javascript:void(0);" class="btn btn-sm" onclick="customConfirm(\'' . $table . '\', \'' . $row[$idColumnName] . '\');"><i class="fi fi-br-trash"></i></a>';
                                        echo '<script>
                                        function generateRandomString(length) {
                                            var result           = "";
                                            var characters       = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                            var charactersLength = characters.length;
                                            for ( var i = 0; i < length; i++ ) {
                                                result += characters.charAt(Math.floor(Math.random() * charactersLength));
                                            }
                                            return result;
                                        }
                                    
                                        function customConfirm(table, id) {
                                            var randomString = generateRandomString(8); // Generates a random 8 characters string
                                            var userInput = prompt("Por favor, escreva este texto para confirmar a exclusão: " + randomString);
                                            if(userInput === randomString) {
                                                window.location.href = "tableDelete.php?table=" + table + "&id=" + id;
                                            }
                                        }
                                        </script>';
                                    }
                                    echo '</td>';
                                }
                                echo '</tr>';
                            }

                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Check if add action is allowed -->
            <?php if ($tablePermissions[$table]['addable']): ?>
            <div class="absolute right-10 bottom-10">
                <a href="tableAdd.php?table=<?php echo $table ?>" class="btn btn-lg btn-circle">
                    <i class="fi fi-br-plus mt-1"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script>
        $('#close-error').on('click', function() {
            $('.error-container').remove();
        });
    </script>
      <script>
    $(document).ready(function() {
      $('#table-View').DataTable(
        {
          "scrollX": false,
          "paging": true,
          "pageLength": 10,
          "ordering": true,
          "info": false,
          "lengthChange": false,
          "searching": true,
        }
      );
    });
    </script>

<?php
    require 'includes/footer.inc.php';