<h2>Users</h2>
<div class="table-responsive">
    <table class="table table-sm table-hover">
        <thead class="bg-dark text-light">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Change Role</th>
                <th scope="col">Login Permission</th>
                <th scope="col">Login/Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $row='';
            foreach ($dat as $val) {
                if ($val['role']!='admin') {
                    $row .= '<tr>
                            <th scope="row">'.$val['id'].'</th>
                            <td>'.$val['name'].'</td>
                            <td>'.$val['email'].'</td>
                            <td>'.$val['role'].'</td>';

                    if ($val['role']=='writer') {
                        $row .= '<td><a href="http://localhost:8080/admin/changeRole&currentSection=users&newRole=reader&userId='.$val['id'].'" class="btn-sm btn-warning">user</a></td>';
                    } elseif ($val['role']=='user') {
                        $row .= '<td><a href="http://localhost:8080/admin/changeRole&currentSection=users&newRole=writer&userId='.$val['id'].'" class="btn-sm btn-success">writer</a></td>';
                    }

                    $row.='<td>'.$val['permission'].'</td>';

                    if ($val['permission']=='approved') {
                        $row .= '<td><a href="http://localhost:8080/admin/changePermission&currentSection=users&newPer=restricted&userId='.$val['id'].'" class="btn-sm btn-danger">restrict</a></td>';
                    } else {
                        $row .= '<td><a href="http://localhost:8080/admin/changePermission&currentSection=users&newPer=approved&userId='.$val['id'].'" class="btn-sm btn-success">Approve</a></td>';
                    }
                }
                $row.='</tr>';
            }
            echo $row;
            ?>
            
        </tbody>
    </table>
</div>