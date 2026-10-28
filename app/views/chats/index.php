<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
// Short time label for the chat list: now, 5m, 3h, Yesterday, Oct 28
if (!function_exists('chatTimeLabel')) {
    function chatTimeLabel($ts) {
        if (empty($ts)) return '';
        $diff = time() - strtotime($ts);
        if ($diff < 60)     return 'now';
        if ($diff < 3600)   return floor($diff / 60) . 'm';
        if ($diff < 86400)  return floor($diff / 3600) . 'h';
        if ($diff < 172800) return 'Yesterday';
        return date('M j', strtotime($ts));
    }
}
?>

<script>
    // lets the CSS know this is the chats screen (and whether a conversation is open)
    document.body.classList.add('chat-screen');
    <?php if ($chatWith): ?>document.body.classList.add('chat-open');<?php endif; ?>
</script>

<style>
    /* ===== Phone / tablet chat (under 992px): works like Messenger / Instagram =====
       - no conversation open -> the chat list fills the screen
       - conversation open    -> full-screen chat with a back arrow, the message box pinned at the bottom
       Desktop (992px and up) keeps the two-panel layout. */
    .chat-back { font-size: 1.5rem; line-height: 1; padding: 2px 10px 2px 0; text-decoration: none; color: var(--text-primary) !important; }

    @media (max-width: 991.98px) {
        body.chat-screen footer { display: none; }

        /* ---- list screen ---- */
        .chat-page { height: auto !important; padding-top: 12px !important; padding-bottom: 84px; }
        .chat-page .chat-card, .chat-page .chat-row { height: auto !important; }
        .chat-list-col, .chat-thread-col { flex: 0 0 100%; max-width: 100%; width: 100%; }
        body.chat-screen:not(.chat-open) .chat-thread-col { display: none !important; }

        /* ---- conversation screen (full screen, above the header and bottom bar) ---- */
        body.chat-open { overflow: hidden; }
        body.chat-open .mobile-nav { display: none !important; }
        body.chat-open .chat-list-col { display: none !important; }
        body.chat-open .chat-page { padding: 0 !important; }
        body.chat-open .chat-card {
            backdrop-filter: none !important; -webkit-backdrop-filter: none !important;  /* a blurred parent would trap the fixed panel */
            box-shadow: none !important; overflow: visible !important;
        }
        body.chat-open .chat-thread-col {
            position: fixed; top: 0; left: 0; right: 0;
            height: 100vh; height: 100dvh;
            z-index: 3000;
            display: flex !important; flex-direction: column;
            background: var(--bg-base);
        }
        .chat-thread-col .chat-head { flex: 0 0 auto; padding: 10px 14px !important; }
        .chat-thread-col .chat-box  { flex: 1 1 auto; min-height: 0; overflow-y: auto; overscroll-behavior: contain; padding: 12px !important; }
        .chat-thread-col .chat-input { flex: 0 0 auto; padding: 10px 12px calc(10px + env(safe-area-inset-bottom)) !important; }
        #chatInput { font-size: 16px; }   /* stops iPhones from zooming in when you tap the box */

        /* tighter bubbles */
        #chatBox .rounded-4 { padding: .5rem .8rem !important; max-width: 80% !important; border-radius: 1.1rem !important; }
        #chatBox .mb-3 { margin-bottom: .4rem !important; }
    }
</style>

<div class="row justify-content-center pt-4 chat-page" style="height: calc(100vh - 100px);">
    <div class="col-lg-10">
        <div class="card shadow-lg border-0 h-100 chat-card" style="background: var(--glass-bg); border: 1px solid var(--glass-chrome-1) !important; overflow: hidden;">
            <div class="row g-0 h-100 chat-row">
                
                <!-- LEFT: Users List (latest conversation first) -->
                <div class="col-md-4 border-end chat-list-col" style="border-color: var(--glass-chrome-1) !important; overflow-y: auto;">
                    <div class="p-3 border-bottom" style="border-color: var(--glass-chrome-1) !important;">
                        <h5 class="fw-bold mb-0 text-white"><i class="bi bi-chat-dots me-2"></i> Chats</h5>
                    </div>
                    <div class="list-group list-group-flush" id="chatList">
                        <?php foreach ($users as $u): ?>
                            <?php
                                $unread    = (int)($u['unread_count'] ?? 0);
                                $hasChat   = !empty($u['last_message']);
                                $preview   = $hasChat
                                    ? (($u['last_sender_id'] == $_SESSION['user_id'] ? 'You: ' : '') . $u['last_message'])
                                    : '@' . $u['username'];
                            ?>
                            <a href="index.php?page=chats&with=<?= (int)$u['id'] ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3 p-3 text-decoration-none" style="background: <?= (isset($chatWith) && $chatWith['id'] == $u['id']) ? 'rgba(142, 182, 155, 0.15)' : 'transparent' ?>; border-color: var(--glass-chrome-1); color: var(--text-primary);">
                                <?php if (!empty($u['profile_image']) && $u['profile_image'] !== 'default.png'): ?>
                                    <img src="assets/uploads/<?= htmlspecialchars($u['profile_image']) ?>" class="rounded-circle flex-shrink-0" style="width: 45px; height: 45px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px;">
                                        <?= htmlspecialchars(strtoupper(substr($u['full_name'], 0, 1))) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                        <div class="<?= $unread > 0 ? 'fw-bold' : 'fw-semibold' ?> text-truncate"><?= htmlspecialchars($u['full_name']) ?></div>
                                        <small class="text-muted flex-shrink-0" style="font-size: 0.7rem;"><?= htmlspecialchars(chatTimeLabel($u['last_time'] ?? null)) ?></small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                        <small class="text-truncate <?= $unread > 0 ? 'text-white fw-bold' : 'text-muted' ?>"><?= htmlspecialchars($preview) ?></small>
                                        <?php if ($unread > 0): ?>
                                            <span class="badge bg-danger rounded-pill flex-shrink-0"><?= $unread ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- RIGHT: Chat Window -->
                <div class="col-md-8 d-flex flex-column chat-thread-col" style="background: var(--bg-base);">
                    <?php if ($chatWith): ?>
                        <!-- Chat Header -->
                        <div class="p-3 border-bottom d-flex align-items-center gap-3 chat-head" style="border-color: var(--glass-chrome-1) !important;">
                            <a href="index.php?page=chats" class="chat-back d-lg-none" aria-label="Back to chats"><i class="bi bi-arrow-left"></i></a>
                            <?php if (!empty($chatWith['profile_image']) && $chatWith['profile_image'] !== 'default.png'): ?>
                                <img src="assets/uploads/<?= htmlspecialchars($chatWith['profile_image']) ?>" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <?= htmlspecialchars(strtoupper(substr($chatWith['full_name'], 0, 1))) ?>
                                </div>
                            <?php endif; ?>
                            <h6 class="mb-0 fw-bold text-white"><?= htmlspecialchars($chatWith['full_name']) ?></h6>
                        </div>

                        <!-- Messages Area -->
                        <div class="flex-grow-1 p-4 chat-box" style="overflow-y: auto;" id="chatBox">
                            <?php foreach ($messages as $msg): ?>
                                <?php $isMine = ($msg['sender_id'] == $_SESSION['user_id']); ?>
                                <div class="d-flex mb-3 <?= $isMine ? 'justify-content-end' : 'justify-content-start' ?>">
                                    <div class="p-3 rounded-4" style="max-width: 75%; background: <?= $isMine ? 'var(--accent)' : 'var(--glass-bg-2)' ?>; color: <?= $isMine ? '#051F20' : 'var(--text-primary)' ?>;">
                                        <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                        <div class="text-end mt-1" style="font-size: 0.65rem; opacity: 0.7;">
                                            <?= date('g:i A', strtotime($msg['created_at'])) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Message Input -->
                        <div class="p-3 border-top chat-input" style="border-color: var(--glass-chrome-1) !important;">
                            <form method="POST" action="index.php?page=chats.send" class="d-flex gap-2" id="chatForm">
                                <input type="hidden" name="receiver_id" value="<?= (int)$chatWith['id'] ?>">
                                <input type="text" name="message" id="chatInput" class="form-control" placeholder="Type a message..." required autocomplete="off" enterkeyhint="send" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); border-radius: 50px; color: white;">
                                <button type="submit" class="btn btn-primary" style="border-radius: 50%; width: 45px; height: 45px; padding: 0;"><i class="bi bi-send"></i></button>
                            </form>
                        </div>
                    <?php else: ?>
                        <!-- Empty State -->
                        <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                            <i class="bi bi-chat-square-dots" style="font-size: 4rem; opacity: 0.5;"></i>
                            <h4 class="mt-3">Your Messages</h4>
                            <p>Select a conversation to start chatting.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var CHAT_WITH = <?= ($chatWith) ? (int)$chatWith['id'] : 0 ?>;
    var ME        = <?= (int)$_SESSION['user_id'] ?>;
    var chatBox   = document.getElementById('chatBox');
    var chatList  = document.getElementById('chatList');

    // Auto-scroll to bottom of chat on first load
    if (chatBox) { chatBox.scrollTop = chatBox.scrollHeight; }

    // ---------- helpers (all text is set with textContent, so it is XSS-safe) ----------
    function parseTime(s) { return new Date(String(s).replace(' ', 'T')); }

    function timeLabel(s) {
        if (!s) return '';
        var diff = (Date.now() - parseTime(s).getTime()) / 1000;
        if (diff < 60)     return 'now';
        if (diff < 3600)   return Math.floor(diff / 60) + 'm';
        if (diff < 86400)  return Math.floor(diff / 3600) + 'h';
        if (diff < 172800) return 'Yesterday';
        return parseTime(s).toLocaleDateString([], { month: 'short', day: 'numeric' });
    }

    function el(tag, className, text) {
        var e = document.createElement(tag);
        if (className) e.className = className;
        if (text !== undefined) e.textContent = text;
        return e;
    }

    function avatar(u, size) {
        var hasImg = u.profile_image && u.profile_image !== 'default.png';
        if (hasImg) {
            var img = el('img', 'rounded-circle flex-shrink-0');
            img.src = 'assets/uploads/' + encodeURIComponent(u.profile_image);
            img.style.cssText = 'width:' + size + 'px;height:' + size + 'px;object-fit:cover;';
            return img;
        }
        var d = el('div', 'rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0',
                   (u.full_name || '?').charAt(0).toUpperCase());
        d.style.cssText = 'width:' + size + 'px;height:' + size + 'px;';
        return d;
    }

    // ---------- chat list ----------
    function renderList(users, me) {
        chatList.innerHTML = '';
        users.forEach(function (u) {
            var unread  = parseInt(u.unread_count, 10) || 0;
            var hasChat = !!u.last_message;
            var preview = hasChat
                ? ((String(u.last_sender_id) === String(me) ? 'You: ' : '') + u.last_message)
                : '@' + u.username;

            var a = el('a', 'list-group-item list-group-item-action d-flex align-items-center gap-3 p-3 text-decoration-none');
            a.href = 'index.php?page=chats&with=' + u.id;
            a.style.cssText = 'background:' + (String(u.id) === String(CHAT_WITH) ? 'rgba(142,182,155,0.15)' : 'transparent') +
                              ';border-color:var(--glass-chrome-1);color:var(--text-primary);';

            a.appendChild(avatar(u, 45));

            var body = el('div', 'flex-grow-1');
            body.style.minWidth = '0';

            var row1 = el('div', 'd-flex justify-content-between align-items-center gap-2');
            row1.appendChild(el('div', (unread > 0 ? 'fw-bold' : 'fw-semibold') + ' text-truncate', u.full_name));
            var t = el('small', 'text-muted flex-shrink-0', timeLabel(u.last_time));
            t.style.fontSize = '0.7rem';
            row1.appendChild(t);

            var row2 = el('div', 'd-flex justify-content-between align-items-center gap-2');
            row2.appendChild(el('small', 'text-truncate ' + (unread > 0 ? 'text-white fw-bold' : 'text-muted'), preview));
            if (unread > 0) row2.appendChild(el('span', 'badge bg-danger rounded-pill flex-shrink-0', String(unread)));

            body.appendChild(row1);
            body.appendChild(row2);
            a.appendChild(body);
            chatList.appendChild(a);
        });
    }

    // ---------- messages ----------
    var stickToBottom = false;   // true right after you send, so the view jumps to your new message

    function messageRow(m, me) {
        var mine = String(m.sender_id) === String(me);
        var wrap = el('div', 'd-flex mb-3 ' + (mine ? 'justify-content-end' : 'justify-content-start'));
        var bubble = el('div', 'p-3 rounded-4');
        bubble.style.cssText = 'max-width:75%;background:' + (mine ? 'var(--accent)' : 'var(--glass-bg-2)') +
                               ';color:' + (mine ? '#051F20' : 'var(--text-primary)') + ';';

        // keep line breaks without using innerHTML
        String(m.message).split('\n').forEach(function (line, i) {
            if (i > 0) bubble.appendChild(document.createElement('br'));
            bubble.appendChild(document.createTextNode(line));
        });

        var time = el('div', 'text-end mt-1',
            parseTime(m.created_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' }));
        time.style.cssText = 'font-size:0.65rem;opacity:0.7;';
        bubble.appendChild(time);

        wrap.appendChild(bubble);
        return wrap;
    }

    function renderMessages(msgs, me) {
        if (!chatBox) return;
        var nearBottom = (chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight) < 80;
        chatBox.innerHTML = '';

        msgs.forEach(function (m) { chatBox.appendChild(messageRow(m, me)); });

        if (nearBottom || stickToBottom) { chatBox.scrollTop = chatBox.scrollHeight; stickToBottom = false; }
    }

    // ---------- auto-refresh every 3 seconds ----------
    var lastListSig = '';
    var lastMsgSig  = '';

    function poll(force) {
        if (document.hidden && !force) return;

        var url = 'index.php?page=chats&ajax=1' + (CHAT_WITH ? '&with=' + CHAT_WITH : '');
        fetch(url, { credentials: 'same-origin' })
            .then(function (r) { return r.ok ? r.json() : null; })
            .then(function (data) {
                if (!data) return;

                var listSig = JSON.stringify(data.users.map(function (u) {
                    return [u.id, u.last_time, u.last_message, u.unread_count];
                }));
                if (listSig !== lastListSig) {
                    // the first poll only stores the signature (page already shows the same list)
                    if (lastListSig !== '' || force) renderList(data.users, data.me);
                    lastListSig = listSig;
                }

                if (CHAT_WITH) {
                    var last = data.messages.length ? data.messages[data.messages.length - 1] : null;
                    var msgSig = data.messages.length + '|' + (last ? last.created_at + last.message : '');
                    if (msgSig !== lastMsgSig) {
                        if (lastMsgSig !== '' || force) renderMessages(data.messages, data.me);
                        lastMsgSig = msgSig;
                    }
                }
            })
            .catch(function () { /* ignore network hiccups, try again next tick */ });
    }

    // ---------- send without reloading the page ----------
    var form  = document.getElementById('chatForm');
    var input = document.getElementById('chatInput');
    var sending = false;

    if (form && input && chatBox) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var text = input.value.trim();
            if (text === '' || sending) return;
            sending = true;

            var fd = new FormData(form);
            fd.set('message', text);
            fd.append('ajax', '1');

            // show it straight away (slightly faded until the server confirms)
            var pending = messageRow({ sender_id: ME, message: text, created_at: new Date().toISOString() }, ME);
            pending.style.opacity = '.6';
            chatBox.appendChild(pending);
            chatBox.scrollTop = chatBox.scrollHeight;
            input.value = '';
            input.focus();                                   // keeps the keyboard open, like Messenger

            fetch('index.php?page=chats.send', { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(function (r) { if (!r.ok) throw new Error('http'); return r.text(); })
                .then(function (t) {
                    var ok = true;
                    try { ok = JSON.parse(t).status === 'ok'; } catch (err) { /* older controller: it redirected, the message was still saved */ }
                    if (!ok) throw new Error('rejected');
                    stickToBottom = true;
                    poll(true);
                })
                .catch(function () {
                    pending.remove();
                    input.value = text;
                    alert('Message not sent. Please try again.');
                })
                .then(function () { sending = false; });
        });
    }

    // On phones the open chat is a full-screen panel. Match it to the VISIBLE screen so the
    // on-screen keyboard never covers the message box.
    var panel = document.querySelector('.chat-thread-col');
    var vv    = window.visualViewport;
    var small = window.matchMedia('(max-width: 991.98px)');

    function fitPanel() {
        if (!panel || !CHAT_WITH) return;
        if (!small.matches || !vv) { panel.style.height = ''; panel.style.top = ''; return; }
        var atBottom = chatBox && (chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight) < 120;
        panel.style.top    = vv.offsetTop + 'px';
        panel.style.height = vv.height + 'px';
        if (atBottom) chatBox.scrollTop = chatBox.scrollHeight;
    }
    if (vv) { vv.addEventListener('resize', fitPanel); vv.addEventListener('scroll', fitPanel); }
    if (small.addEventListener) small.addEventListener('change', fitPanel); else small.addListener(fitPanel);
    fitPanel();

    poll();
    setInterval(poll, 3000);
})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>