<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script src="<?=$_E['SITEROOT']?>js/third/bignumber.min.js"></script>
<script src="<?=$_E['SITEROOT']?>js/third/crypto-js/rollups/aes.js"></script>
<script src="<?=$_E['SITEROOT']?>js/third/crypto-js/rollups/md5.js"></script>
<script src="<?=$_E['SITEROOT']?>js/third/crypto-js/components/pad-zeropadding.js"></script>
<script>
GA = new BigNumber('<?=$tmpl['dh_ga']?>');
PublicPrime = new BigNumber('<?=$tmpl['dh_prime']?>');
PublicG = new BigNumber('<?=$tmpl['dh_g']?>');

function PowMod(a,e,m)
{
    //a!=0 always
    res = new BigNumber(1);
    while( !e.eq(0) )
    {
        if( e.mod(2).eq(1) )
            res = res.mul(a).mod(m);
        a = a.mul(a).mod(m);
        e = e.div(2).floor();
    }
    return res;
}

function login_by_system(){
    
}

$(document).ready(function()
{
    $("#loginform").submit(function(e)
    {
        e.preventDefault();

        $("#display").html('...');
        
        msg = $("#passwordreal").val();
        $("#password").val(msg);
        
        api_submit("<?=$TnfshAttend->uri('user','login')?>","#loginform","#display",function(res){
            location.href = "<?=$_E['SITEROOT']?>"+res.data;
        });
        this.passwordreal.disabled = false;
        return true;
    });
});
</script>
<div class="container">
    <div class= "row">
        <div class="col-lg-offset-4 col-lg-4 login_form"><!--mask-->
            <center>
                <h3>使用者登入</h3>
                
                <form role="form" action="user.php" method="post" id="loginform">
                
                    <input type="hidden" value="login" name="mod">
                    <input type="hidden" value="" name="GB" id="GB">
                    <input type="hidden" value="" name="password" id="password">
                    <br>
                    
                    <div class="form-group">
                    <label for="username" style = "display: block" class="login_lable_text">帳號</label>
                    <input type="text" class="textinput" id="username" name="username" placeholder="Username" required>
                    </div>
                    
                    <div class="form-group">
                    <label for="password" style = "display: block" class="login_lable_text">密碼</label>
                    <input type="password" class="textinput" id="passwordreal" placeholder="Password" required>
                    </div>
                    
                    <br>
                    <div>
                        <small><span id="display"></span></small>
                    </div>
                    <div class="form-group">
                        <button type="submit" class= "btn-grn btn-large btn-wide" style = "width:168px">
                        <b>Login</b>
                        </button>
                    </div>
                    
                </form>
                
                </div>
            </center>
        </div>
    </div>
</div>