$(document).ready(function(){
    function validate() {
		if (document.forms['login']['email'].value == "" || document.forms['login']['password'].value == "") {
			alert("Please fill out email or password field");
			return false;
		}
	}
    
    function validateDate($date) {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }
        return false;
    }
    
    function clear(){
        $("#user_id").val("");
        $("#username").val("");
        $("#email").val("");
        $("#password").val("");
    }
    
    $("table tr").click(function(){
        $("table tr").css("font-weight","normal");
        $(this).css("font-weight","bold");
        $("#user_id").val($(this).find("td").eq(0).html());
        $("#username").val($(this).find("td").eq(1).html());
        $("#email").val($(this).find("td").eq(2).html());
        $("#password").val($(this).find("td").eq(3).html());
        $("#del").removeAttr("disabled").css("color","black");
        $("#edit").removeAttr("disabled").css("color","black");
        $("#info").hide();
    });
    
    $("#del").attr("disabled","disabled").css("color","gray");
    $("#edit").attr("disabled","disabled").css("color","gray");
    $("#add").click(function(){
        $("table tr").css("font-weight","normal");
        $.get("add.php", function(data){
            $("#user_id").val(data);
        });
        $("#del").attr("disabled","disabled").css("color","gray");
        $("#edit").attr("disabled","disabled").css("color","gray");
        clear();
    });
    $(document).click(function(){
        $("#result").html('');
    });
});