import Modal from 'IServ.Modal';
import Routing from 'IServ.Routing';
import Message from 'IServ.Message';

IServ.EspT = IServ.register(function() {

    let modal;

    function openInviteModal(id) {
        modal = Modal.createFromForm({
            'remote': Routing.generate('espt_invite', {id: id}),
            'id': 'espt_invite_' + id,
            'size': 'lg',
            'title': _('espt_timeslot_type_invite'),
            'onSuccess': function ($modal, data, options) {
                switch (data.status) {
                    case 'success':
                        closeInviteModal();
                        Message.success(_('espt_invited'), 5000, false);
                        break;
                    case 'error':
                        Message.error(data.message, false, false);
                        break;
                    default:
                        Message.error(_('Unknown response!'), false, false);
                        break;
                }
            },
        });
        modal.show();
    }

    function closeInviteModal() {
        modal.hide();
        modal.destroy();
    }

    function initialize() {

    }


    return {
        init: initialize,
        openInviteModal
    };

}());

export default IServ.EspT;
