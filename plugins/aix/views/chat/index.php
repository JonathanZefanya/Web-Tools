<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('chats') ?>"><?= l('chats.breadcrumb') ?></a><i class="fa fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('chat.breadcrumb') ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-truncate mb-0"><?= sprintf(l('chat.header'), $data->chat->name) ?></h1>

        <div class="d-flex align-items-center col-auto p-0">
            <div>
                <a href="<?= url('chat-create') ?>" class="btn btn-primary">
                    <i class="fa fa-fw fa-sm fa-plus-circle"></i> <?= l('chats.create') ?>
                </a>
            </div>

            <?= include_view(\Altum\Plugin::get('aix')->path . 'views/chats/chat_dropdown_button.php', ['id' => $data->chat->chat_id, 'resource_name' => $data->chat->name]) ?>
        </div>
    </div>

    <div id="chat_messages_wrapper" class="card mb-4 <?= count($data->chat_messages) ? null : 'd-none' ?>">
        <div class="card-body">
            <div id="chat_messages">
                <?php foreach($data->chat_messages as $chat_message): ?>
                <div class="<?= $chat_message->role == 'user' ? '' : 'bg-gray-100' ?> p-3 rounded d-flex mb-3">
                    <div class="mr-3">
                        <img src="<?= $chat_message->role == 'user' ? get_gravatar($this->user->email) : (settings()->aix->chats_avatar ? \Altum\Uploads::get_full_url('chats_avatar') . settings()->aix->chats_avatar : get_gravatar('')) ?>" class="ai-chat-avatar rounded" loading="lazy" />
                    </div>

                    <div>
                        <div class="font-weight-bold small <?= $chat_message->role == 'user' ? 'text-primary' : 'text-muted' ?>"><?= $chat_message->role == 'user' ? $this->user->name : settings()->aix->chats_assistant_name ?></div>
                        <div class="chat-content"><?= nl2br(e($chat_message->content)) ?></div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <div class="card ">
        <div class="card-body">

            <form id="chat_message_create" action="" method="post" role="form">
                <input type="hidden" name="global_token" value="<?= \Altum\Csrf::get('global_token') ?>" />
                <input type="hidden" name="chat_id" value="<?= $data->chat->chat_id ?>" />

                <div class="notification-container"></div>

                <div class="input-group">
                    <input type="text" name="content" class="form-control" placeholder="<?= l('chats.content_placeholder') ?>">
                    <div class="input-group-append">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><i class="fa fa-fw fa-sm fa-paper-plane mr-1"></i> <?= l('global.submit') ?></button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<template id="template_chat_message_user">
    <div class="p-3 rounded d-flex mb-3 altum-animate altum-animate-fill-none altum-animate-fade-in">
        <div class="mr-3">
            <img src="<?= get_gravatar($this->user->email) ?>" class="ai-chat-avatar rounded" loading="lazy" />
        </div>

        <div>
            <span class="font-weight-bold small text-primary"><?= $this->user->name ?></span>
            <div class="chat-content"></div>
        </div>
    </div>
</template>

<template id="template_chat_message_assistant">
    <div class="bg-gray-100 p-3 rounded d-flex mb-3 altum-animate altum-animate-fill-none altum-animate-fade-in">
        <div class="mr-3">
            <img src="<?= (settings()->aix->chats_avatar ? \Altum\Uploads::get_full_url('chats_avatar') . settings()->aix->chats_avatar : get_gravatar('')) ?>" class="ai-chat-avatar rounded" loading="lazy" />
        </div>

        <div>
            <span class="font-weight-bold small text-muted"><?= settings()->aix->chats_assistant_name ?></span>
            <div class="chat-content"></div>
        </div>
    </div>
</template>

<?php ob_start() ?>
<script>
    /* Form submission */
    document.querySelector('#chat_message_create').addEventListener('submit', async event => {
        event.preventDefault();

        pause_submit_button(event.target.querySelector('[type="submit"][name="submit"]'));

        /* Notification container */
        let notification_container = event.target.querySelector('.notification-container');
        notification_container.innerHTML = '';

        /* Make sure the chat messages wrapper is shown */
        document.querySelector('#chat_messages_wrapper').classList.remove('d-none');

        /* Prepare form data */
        let form = new FormData(document.querySelector('#chat_message_create'));

        /* Add user message */
        let clone = document.querySelector(`#template_chat_message_user`).content.cloneNode(true);
        clone.querySelector('.chat-content').innerText = form.get('content');
        document.querySelector(`#chat_messages`).appendChild(clone);

        /* Send request to server */
        let response = await fetch(`${url}chat/create_ajax`, {
            method: 'post',
            body: form
        });

        let data = null;
        try {
            data = await response.json();
        } catch (error) {
            enable_submit_button(event.target.querySelector('[type="submit"][name="submit"]'));
            display_notifications(<?= json_encode(l('global.error_message.basic')) ?>, 'error', notification_container);
            notification_container.scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.querySelector(`#chat_messages`).lastElementChild.remove();
        }

        if(!response.ok) {
            enable_submit_button(event.target.querySelector('[type="submit"][name="submit"]'));
            display_notifications(<?= json_encode(l('global.error_message.basic')) ?>, 'error', notification_container);
            notification_container.scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.querySelector(`#chat_messages`).lastElementChild.remove();
        }

        if (data.status == 'error') {
            enable_submit_button(event.target.querySelector('[type="submit"][name="submit"]'));
            display_notifications(data.message, 'error', notification_container);
            notification_container.scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.querySelector(`#chat_messages`).lastElementChild.remove();
        } else if (data.status == 'success') {

            /* Add assistant message */
            clone = document.querySelector(`#template_chat_message_assistant`).content.cloneNode(true);
            clone.querySelector('.chat-content').innerText = data.details.content;
            document.querySelector(`#chat_messages`).appendChild(clone);

            /* Clear message input */
            document.querySelector('input[name="content"]').value = '';

            enable_submit_button(event.target.querySelector('[type="submit"][name="submit"]'));
        }
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>


<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'chat',
    'resource_id' => 'chat_id',
    'has_dynamic_resource_name' => true,
    'path' => 'chats/delete'
]), 'modals'); ?>

