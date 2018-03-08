<!-- cont main -->
    <div class="container animated fadeIn">
        <?php

        if ($User->GrowerOperation->permission == 2 && $User->GrowerOperation->type != 'none') {

            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Your team members
                    </div>

                    <div class="page-description text-muted small">
                        You can allow others to act on behalf of your operation by inviting them to join your team. 'Owners' have full control over an operation whereas 'Managers' can only manage listings.
                    </div>
                </div>
            </div>

            <hr>

            <div class="alerts"></div>

            <form id="invite-member">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    Invite a new team member
                                </label>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Email address" data-parsely-type="email" data-parsley-trigger="change">
                                </div>
                            </div>
                                
                            <div class="col-md-6">
                                <button type="submit" form="invite-member" class="btn btn-success btn-block">
                                    <i class="pre fa fa-share float-left"></i>
                                    Invite
                                    &emsp;
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Role</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach($team_members as $team_member) { ?>
                                        <tr>
                                            <td>
                                                <?php

                                                switch($team_member['permission']) {
                                                    case 0:
                                                        $role = 'invited';
                                                        break;
                                                    case 1:
                                                        $role = 'manager';
                                                        break;
                                                    case 2:
                                                        $role = 'owner';
                                                        break;
                                                }

                                                echo ucfirst($role);
                                                
                                                ?>
                                            </td>
                                            <td><?php echo $team_member['first_name']; ?></td>
                                            <td><?php echo $team_member['last_name']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

            <?php

        } else {
            echo 'Oops! You\'re not supposed to be here.';
        }

        ?>
    </div>
</main>