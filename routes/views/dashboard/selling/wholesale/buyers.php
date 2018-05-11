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

        <?php if (!empty($wholesale_relationships)): ?>

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
                                    
                                    <?php foreach($wholesale_relationships as $wholesale_relationship): ?>

                                        <?php $BuyerAccount = new BuyerAccount([
                                            'DB' => $DB,
                                            'id' => $wholesale_relationship['buyer_account_id']
                                        ]); ?>

                                        <?php switch($wholesale_relationship['status']) {
                                            case 0:
                                                $status = [
                                                    'readable'  => 'denied',
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

                                        <tr data-relationship-id="<?= $wholesale_relationship['id'] ?>">
                                            <td class="status">
                                                <span class="<?= $status['span'] ?>">
                                                    <?= ucfirst($status['readable']) ?>
                                                </span>
                                            </td>

                                            <td>
                                                <?= $BuyerAccount->name ?>
                                            </td>

                                            <td class="float-right">
                                                <div class="btn btn-success approve-buyer <?php if ($status['readable'] == 'approved') echo 'hidden' ?>">
                                                    Approve
                                                </div>
                                            
                                                <div class="btn btn-danger unapprove-buyer <?php if ($status['readable'] == 'denied') echo 'hidden' ?>">
                                                    Deny
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

        <?php else: ?>

            <div class="block margin-top-1em strong">
                You don't have any wholesale buyers
            </div>

        <?php endif; ?>

    </div>
</main>