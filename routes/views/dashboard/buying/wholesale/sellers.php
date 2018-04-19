<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Your wholesale sellers
                </div>

                <div class="page-description text-muted small">
                    These are the approved, unapproved, and pending wholesale sellers whose discounted item prices you have access to.
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

                                    <?php $Seller = new GrowerOperation([
                                        'DB' => $DB,
                                        'id' => $wholesale_membership['seller_id']
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
                                            <?= $Seller->name ?>
                                        </td>

                                        <td>
                                            <a href="<?= PUBLIC_ROOT . $Seller->link ?>">
                                                <i class="fa fa-external-link" data-toggle="tooltip" data-title="View seller profile" data-placement="left"></i>
                                            </a>
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