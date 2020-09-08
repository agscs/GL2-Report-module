<?php
class reportTemplate extends template {
	public $reportFormTPL = '
            <div class="panel">
                <div class="panel-head">Reporting {user.name}</div>
                <div class="panel-body">
                    <form method="post" action="?page=report&action=report">
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="pull-left"><strong>Reported User</strong> {user.name}</label>
									<input type="text" class="form-control" name="reported_user" value="{user.id}" />
								</div>
							</div>
							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="pull-left"><strong>Reason</strong></label>
									<select name="report_reason" class="form-control">
										{#each reasons}
										<option value="{id}">{name}</option>
										{/each}
									</select>
								</div>
							</div>
						</div>
                        <div class="form-group">
                            <label class="pull-left"><strong>Report</strong></label>
							<br><small>Give more details in the box.</small>
                            <textarea class="form-control" name="report_text" rows="10" data-editor></textarea>
                            <div class="text-right">
                                <small>[BBCode] Enabled</small>
                            </div>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-default" name="submit" type="submit" value="1">Report</button>
                        </div>
                    </form>
                </div>
            </div>
 	';
	public $reportList = '
            <table class="table table-condensed table-responsive table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="30px">#</th>
                        <th width="100px">Reported</th>
                        <th width="150px">Reason</th>
                        <th>text</th>
                        <th width="100px">By</th>
                        <th width="40px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each Reports}
                        <tr>
                            <td>{id}</td>
                            <td><a href="?page=profile&view={user.id}" target="_blank">{user.name}</a></td>
                            <td>{reason}</td>
                            <td>{text}</td>
                            <td><a href="?page=profile&view={by.id}" target="_blank">{by.name}</a></td>
                            <td>[<a href="?page=admin&module=report&action=ban&id={id}">Ban</a>]</td>
                        </tr>
                    {/each}
                </tbody>
            </table>
	';
	public $userBan = '
            <form method="post" action="?page=admin&module=report&action=ban&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to Ban this Player?</p>
                    <p><em>"{user.name}"</em> - <a href="?page=profile&view={user.id}" target="_blank">Profile Page</a></p>
                    <button class="btn btn-danger" name="submit" type="submit" value="1">Yes Ban {user.name}</button>
                </div>
            </form>
	';
	public $reasonsList = '
		<div class="row">
			<div class="col-md-6">
				<table class="table table-condensed table-responsive table-striped table-bordered">
					<thead>
						<tr>
							<th width="30px">#</th>
							<th>name</th>
							<th width="40px">Actions</th>
						</tr>
					</thead>
					<tbody>
						{#each Reasons}
							<tr>
								<td>{id}</td>
								<td>{name}</td>
								<td>[<a href="?page=admin&module=report&action=deleteReason&id={id}">Delete</a>]</td>
							</tr>
						{/each}
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<form method="post" action="?page=admin&module=report&action=Reasons">
					<div class="form-group">
						<label class="pull-left">Reason Name</label>
						<input type="text" class="form-control" name="name" value="">
					</div>
					<div class="text-right">
						<button class="btn btn-default" name="submit" type="submit" value="1">Add</button>
					</div>
				</form>
			</div>
		</div>
	';
	public $reasonDelete = '
            <form method="post" action="?page=admin&module=report&action=deleteReason&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to delete this Report Reason?</p>

                    <p><em>"{name}"</em></p>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">
                        Yes delete this Report Reason
                    </button>

                </div>
            </form>
	';
}