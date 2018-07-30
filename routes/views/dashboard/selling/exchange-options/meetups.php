<!-- cont main -->
    <div class="container animated fadeIn">
        
        <div id="meetups" class="<?php if (empty($meetups)) echo 'hidden' ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Your meetups
                    </div>

                    <div class="page-description text-muted small">
                        Let your customers come to you. These are the locations you've set as meetup points.
                    </div>
                </div>
            </div>
            
            <hr>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive margin-btm-2em">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Address</th>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>
                                        Deadline
                                        <i class="fa fa-question-circle" data-toggle="tooltip" data-title="The amount of time prior to this meetup that an order needs to be placed"></i>
                                    </th>
                                    <th>
                                        Minimum order
                                        <i class="fa fa-question-circle" data-toggle="tooltip" data-title="The minimum amount an order needs to be in order to qualify for this meetup"></i>
                                    </th>
                                    <!-- <th>&nsbp;</th> -->
                                </tr>
                            </thead>

                            <tbody class="text-muted">

                                <?php foreach($meetups as $meetup): ?>
                                    
                                    <tr>
                                        <td>
                                            <?= (!empty($meetup['title'])) ? $meetup['title'] : '&ndash;' ?>
                                        </td>
                                        
                                        <td>
                                            <?= "{$meetup['address_line_1']} {$meetup['address_line_2']}, {$meetup['city']}, {$meetup['state']}" ?>
                                        </td>
                                        
                                        <td>
                                            <?= $meetup['day'] ?>
                                        </td>
                                        
                                        <td>
                                            <?= "{$meetup['start_time']} &ndash; {$meetup['end_time']}" ?>
                                        </td>
                                        
                                        <td>
                                            <?= "{$meetup['deadline']} hours" ?>
                                        </td>

                                        <td>
                                            <?= _amount($meetup['order_minimum']) ?>
                                        </td>
                                        
                                        <!-- <td>
                                            <i class="fa fa-trash"></i>
                                        </td> -->
                                    </tr>

                                <?php endforeach ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Add a new meetup
                </div>

                <div class="page-description text-muted small">
                    Specify a convenient location where customers can come meet you to retrieve their orders. Add as many as you like.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="add-meetup" class="btn btn-success">
                        <i class="pre fa fa-plus"></i>
                        Add meetup
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <hr>
        
        <div class="alerts"></div>

        <form id="add-meetup">
            <div class="row">
                <div class="col-md-6">
                    <label>
                        Location
                    </label>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" name="address-line-1" class="form-control" placeholder="Street address" data-parsley-trigger="change" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg." data-parsley-trigger="change">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="city" class="form-control" placeholder="City" data-parsley-trigger="change" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" placeholder="State" data-parsley-pattern="^[a-zA-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="zipcode" class="form-control" placeholder="Zip code" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group margin-btm-50em">
                        <label>
                            Title (optional)
                        </label>

                        <input type="text" name="title" class="form-control" placeholder="Location title" data-parsley-trigger="change">

                        <small class="text-muted">
                            If this meetup is at a recognizable location, specify the name of the address here
                        </small>
                    </div>

                    <label>
                        Time
                    </label>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group margin-0">
                                <select name="day" class="custom-select">
                                    <option selected disabled>Choose a day</option>
                                    <option value="Sunday">Sunday</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group margin-0">
                                <div class="input-group w-addon bootstrap-timepicker timepicker">
                                    <input id="start-time" type="text" name="start-time" class="timepicker form-control" placeholder="Start time">
                                    
                                    <div class="input-group-addon">
                                        <span><i class="fa fa-clock-o"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group margin-0">
                                <div class="input-group w-addon bootstrap-timepicker timepicker">
                                    <input id="end-time" type="text" name="end-time" class="timepicker form-control" placeholder="End time">
                                    
                                    <span class="input-group-addon">
                                        <span><i class="fa fa-clock-o"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <small class="text-muted">
                                Set the time range that you will be at this meetup location
                            </small>    
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            Deadline (optional)
                        </label>

                        <div class="input-group w-addon">
                            <input type="number" name="deadline" class="form-control" placeholder="Order deadline" data-parsley-type="number" data-parsley-trigger="change">
                            
                            <span class="input-group-addon">
                                <span>&emsp;hours&emsp;</span>
                            </span>
                        </div>

                        <small class="text-muted">
                            Enter the amount of time prior to this meetup that orders need to be placed
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            Order minimum (optional)
                        </label>

                        <div class="input-group w-addon">
                            <span class="input-group-addon">
                                <span>
                                    $
                                </span>
                            </span>

                            <input type="text" name="order-minimum" class="form-control" placeholder="Minimum order amount" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-max="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your amount should include both dollars and cents (ex: $2.50)" data-parsley-trigger="change">
                        </div>

                        <small class="text-muted">
                            Enter the minimum amount an order needs to be in order to qualify for this meetup
                        </small>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>