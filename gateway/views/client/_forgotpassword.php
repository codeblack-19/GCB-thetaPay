<!-- Button trigger modal -->
<a class="btn btn-link px-3 me-2" data-mdb-toggle="modal" data-mdb-target="#exampleModal">
  forgot password
</a>

<!-- Modal -->
<div class="modal fade" id="exampleModal" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Forgot password</h5>
        <button type="button" id="modalClBtn" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="chfgPsFm">
            <p id="_message2"></p>
            <div class="form-outline">
              <input type="email" id="fgEmail" class="form-control" name="fgEmail" />
              <label class="form-label" for="fgEmail">Email</label>
            </div>
            <div class="text-center">
              <button class="btn btn-warning acctSettingbtns text-dark" id="sbtfgPsbtn" type="submit">
                Submit
              </button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>