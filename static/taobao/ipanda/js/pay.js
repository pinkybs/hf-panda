var HFPay = {
		post: function(frmId, typeId) {
			var frm = $('#' + frmId);
			if (frm) {
				var type_id = $('#type_id');
				if (type_id) {
					type_id.val(typeId);
				}
				frm.submit();
			}
		}
}
