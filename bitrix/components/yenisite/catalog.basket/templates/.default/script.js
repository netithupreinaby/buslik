function validate()
{
	var form = document.basket;

	if(!form.name.value || !form.email.value || !form.phone.value)
	{
		alert("Не заполненны обязательные поля!");
	}
	else
	{
		var err = 1;

		var emailTest = "^[_\.0-9a-z-A-Z-]+@([0-9a-z][0-9a-z_-]+\.)+[a-z]{2,4}$";
		var regex = new RegExp(emailTest);
		if(!regex.test(form.email.value))
		{
			alert("Неверный E-mail!");
			err=0;
		}

		if(form.name.value.length < 3)
		{
			alert("Неверное имя!");
			err=0;
		}

		if(form.phone.value.length < 6)
		{
			alert("Неверный телефонный номер!");
			err=0;
		}

		if(err)
		{
			form.BasketOrder.value="y";
			form.submit();
			return true;
		}
		else
			return false;
	}
}
