<!-- Button trigger modal -->
<button type="button" id="chPinBtn" class="btn acctSettingbtns" data-mdb-toggle="modal" data-mdb-target="#exampleModal">
  Change Pin Code
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Account Pin Code</h5>
        <button type="button" id="modalClBtn" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="chPcFm">
            <p id="_message2"></p>
            <div class="form-outline mb-3">
                <input type="password" id="pinCode" minlength="6" class="form-control form-control-lg" name="pinCode" />
                <label class="form-label" for="typeEmail">Pin Code</label>
            </div>
            <div class="form-outline mb-3">
                <input type="password" id="verifier" minlength="6" class="form-control form-control-lg" name="verifier" />
                <label class="form-label" for="typePassword">Confirm Pin Code</label>
            </div>
            <button class="btn acctSettingbtns text-dark" id="sbtCPbtn" type="button">
                Submit
            </button>
        </div>
      </div>
    </div>
  </div>
</div>