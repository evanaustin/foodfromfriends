<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Your wholesale buyers
                </div>

                <div class="page-description text-muted small">
                    All <strong>approved</strong> buyers on this list have access to the wholesale prices you have set on each item. You can change any buyer's access at any time.
                </div>
            </div>
        </div>

        <hr>

        <div class="alerts"></div>

        <form id="invite-member">
            <!-- <div class="row"> -->
                <!-- <div class="col-md-6"> -->
                    <!-- <div class="row">
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
                    </div> -->

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Name</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                
                                <?php foreach($wholesale_memberships as $wholesale_membership): ?>

                                    <?php $WholesaleAccount = new WholesaleAccount([
                                        'DB' => $DB,
                                        'id' => $wholesale_membership['wholesale_account_id']
                                    ]); ?>

                                    <?php switch($wholesale_membership['status']) {
                                        case 0:
                                            $status = [
                                                'readable'  => 'not approved',
                                                'span'      => 'red'
                                            ];
                                            break;
                                        case 1:
                                            $status = [
                                                'readable'  => 'requested',
                                                'span'      => 'yellow'
                                            ];
                                            break;
                                        case 2:
                                            $status = [
                                                'readable'  => 'approved',
                                                'span'      => 'green'
                                            ];
                                            break;
                                    } ?>

                                    <tr data-relationship-id="<?= $wholesale_membership['id'] ?>">
                                        <td class="status">
                                            <span class="<?= $status['span'] ?>"><?= ucfirst($status['readable']) ?></span>
                                        </td>

                                        <td>
                                            <?= $WholesaleAccount->name ?>
                                        </td>

                                        <td class="float-right">
                                            <div class="btn btn-success approve-buyer <?php if ($status['readable'] == 'approved') echo 'hidden' ?>">
                                                Approve
                                            </div>
                                        
                                            <div class="btn btn-danger unapprove-buyer <?php if ($status['readable'] == 'not approved') echo 'hidden' ?>">
                                                Unapprove
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                <!-- </div> -->
            <!-- </div> -->
        </form>
    </div>
</main>