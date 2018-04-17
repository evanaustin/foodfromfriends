<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Your wholesale buyers
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
                                Invite a new wholesale buyer
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
                                    <th>Status</th>
                                    <th>Name</th>
                                </tr>
                            </thead>

                            <tbody>
                                
                                <?php foreach($wholesale_memberships as $wholesale_membership): ?>

                                    <?php $WholesaleAccount = new WholesaleAccount([
                                        'DB' => $DB,
                                        'id' => $wholesale_membership['wholesale_account_id']
                                    ]); ?>

                                    <tr>
                                        <td>
                                            <?php

                                            switch($wholesale_memberships['status']) {
                                                case 0:
                                                    $role = 'denied';
                                                    break;
                                                case 1:
                                                    $role = 'requested';
                                                    break;
                                                case 2:
                                                    $role = 'approved';
                                                    break;
                                            }

                                            echo ucfirst($role);
                                            
                                            ?>
                                        </td>

                                        <td><?= $WholesaleAccount->name ?></td>
                                    </tr>

                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>