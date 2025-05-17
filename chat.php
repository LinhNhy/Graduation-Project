<?php 
require_once './config/db.php';

// Nếu là yêu cầu POST (AJAX) thì xử lý và trả về JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    // Hàm kiểm tra xem chuỗi có chứa ký tự tiếng Việt hay không
    function containsVietnamese($text) {
        return preg_match('/[áàảãạăắằẳẵặâấầẩẫậđéèẻẽẹêếềểễệíìỉĩịóòỏõọôốồổỗộơớờởỡợúùủũụưứừửữựýỳỷỹỵ]/i', $text);
    }

    if ($_POST['action'] === 'send_message') {
        $question = trim($_POST['question'] ?? '');
        $selectedLang = trim($_POST['selectedLang'] ?? '');
        if (empty($question)) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập câu hỏi']);
            exit;
        }
        
        // Kiểm tra tính nhất quán giữa nội dung câu hỏi và ngôn ngữ đã chọn
        if ($selectedLang === 'English' && containsVietnamese($question)) {
            echo json_encode(['status' => 'error', 'message' => 'Bạn vừa chọn English. Vui lòng chọn ngôn ngữ phù hợp.']);
            exit;
        }
        if ($selectedLang === 'Vietnamese' && !containsVietnamese($question)) {
            echo json_encode(['status' => 'error', 'message' => 'Bạn vừa chọn Tiếng Việt. Vui lòng chọn ngôn ngữ phù hợp.']);
            exit;
        }
        
        // Xử lý tiền tố cho câu hỏi
        if (empty($selectedLang)) {
            if (containsVietnamese($question)) {
                $modifiedQuestion = "Trả lời bằng tiếng việt: " . $question;
            } else {
                $modifiedQuestion = "Answer in English: " . $question;
            }
        } else {
            $modifiedQuestion = ($selectedLang === 'English') ? "Answer in English: " . $question : "Trả lời bằng tiếng việt: " . $question;
        }
        
        // Gọi API (ví dụ: API của Google hoặc API chatbot khác)
        $apiKey = "AIzaSyDU00AXcsJiLrwcoM2Uv3UH3DzUUSNq-Xk";
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . urlencode($apiKey);

        $postData = json_encode([
            "contents" => [
                [
                    "parts" => [
                        ["text" => $modifiedQuestion]
                    ]
                ]
            ]
        ]);

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData)
            ],
            CURLOPT_POSTFIELDS => $postData
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = 'Lỗi cURL: ' . curl_error($ch);
            echo json_encode(['status' => 'error', 'message' => $error]);
            curl_close($ch);
            exit;
        }

        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatus !== 200) {
            $error = 'Lỗi API: HTTP ' . $httpStatus;
            echo json_encode(['status' => 'error', 'message' => $error]);
            exit;
        }

        $responseData = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            $error = 'Không nhận được phản hồi hợp lệ từ API';
            echo json_encode(['status' => 'error', 'message' => $error]);
            exit;
        }

        // Sử dụng htmlspecialchars để đảm bảo an toàn hiển thị
        $answer = htmlspecialchars($responseData['candidates'][0]['content']['parts'][0]['text'], ENT_QUOTES, 'UTF-8');
        echo json_encode(['status' => 'success', 'message' => $answer]);
        exit;
    }

    if ($_POST['action'] === 'save_rating') {
        $answer = trim($_POST['answer'] ?? '');
        $rating = trim($_POST['rating'] ?? '');
        if (empty($answer) || empty($rating)) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu đánh giá']);
            exit;
        }
        $time = date('Y-m-d H:i:s');

        $stmt = $conn->prepare("INSERT INTO ratings (answer, rating, created_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $answer, $rating, $time);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }
        exit;
    }
}
?>

<!-- Giao diện Chat Widget (xuất ra khi gọi theo GET hoặc khi include vào index.php) -->
<!-- Không bao gồm các thẻ <html>, <head>, <body> để tránh lặp lại -->
<style>
    /* CSS dành cho chat widget */
    .support-icon {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        background: url('sup.png') no-repeat center/cover;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        transition: transform 0.3s ease;
    }
    .support-icon:hover {
        transform: scale(1.1);
    }
    .chat-box {
        display: none;
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 320px;
        max-height: 450px;
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        z-index: 999;
        flex-direction: column;
        transition: width 0.3s ease, max-height 0.3s ease;
    }
    .chat-box.show {
        display: flex;
    }
    .chat-box.expanded {
        width: 400px;
        max-height: 500px;
    }
    .chat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background-color: #f3e1b3;
        color: #333;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        box-shadow: 0 2px 4px rgba(255, 254, 254);
    }
    .chat-header-title {
        font-size: 1em;
        font-weight: 500;
    }
    .online-icon {
        font-size: 0.8em;
        color: #32CD32;
        margin-left: 8px;
    }
    .chat-header-buttons {
        display: flex;
        gap: 10px;
    }
    .chat-header-buttons button {
        width: 24px;
        height: 24px;
        background: none;
        border: none;
        color: #333;
        cursor: pointer;
        font-size: 16px;
        transition: color 0.3s ease;
    }
    .chat-header-buttons button:hover {
        color: #52C0E1;
    }
    .chat-messages {
        flex: 1;
        padding: 15px;
        background-color: #3e5b3f;
        color: #fff;
        overflow-y: auto;
        max-height: 360px;
        position: relative;
    }
    .chat-box.expanded .chat-messages {
        max-height: 410px;
    }
    .message {
        display: flex;
        margin-bottom: 12px;
        animation: fadeIn 0.4s ease;
    }
    .message.user {
        justify-content: flex-end;
    }
    .message.ai {
        justify-content: flex-start;
    }
    .message.error {
        justify-content: center;
    }
    .message-bubble {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 20px;
        font-size: 0.9em;
        line-height: 1.4;
        word-wrap: break-word;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    .message.user .message-bubble {
        background-color: #DCF8C6;
        color: #333;
        border-bottom-right-radius: 4px;
    }
    .message.ai .message-bubble {
        background-color: #fff;
        color: #333;
        border-bottom-left-radius: 4px;
    }
    .message.error .message-bubble {
        background-color: #F8D7DA;
        color: #721C24;
        border-radius: 12px;
        text-align: center;
    }
    .rating-icons {
        margin-top: 5px;
        text-align: right;
    }
    .rating-icons i {
        cursor: pointer;
        margin-left: 10px;
        font-size: 1em;
        color: #888;
        transition: transform 0.2s ease, color 0.3s ease;
    }
    .rating-icons i:hover {
        color: #333;
        transform: scale(1.2);
    }
    @keyframes pop {
        0% { transform: scale(1); }
        50% { transform: scale(1.4); }
        100% { transform: scale(1); }
    }
    .scroll-down-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        border: none;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s ease;
    }
    .scroll-down-btn:hover {
        background-color: rgba(255, 255, 255, 1);
    }
    .scroll-down-btn i {
        font-size: 20px;
        color: #333;
    }
    .chat-input {
        display: flex;
        padding: 12px;
        background-color: #fff;
        border: 2px solid #f3e1b3;
        gap: 10px;
        align-items: center;
    }
    .chat-input textarea {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 20px;
        font-size: 0.8em;
        resize: none;
        height: 36px;
        max-height: 100px;
        line-height: 1.3;
        overflow-y: auto;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .chat-input textarea:focus {
        border-color: #52C0E1;
        box-shadow: 0 0 5px rgba(82, 192, 225, 0.5);
        outline: none;
    }
    .chat-input button {
        width: 32px;
        height: 32px;
        background: url('send.png') no-repeat center/cover;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    .chat-input button:hover {
        transform: scale(1.1);
    }
    .chat-messages::-webkit-scrollbar {
        width: 4px;
    }
    .chat-messages::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: 2px;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .lang-select {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin: 15px auto;
    }
    .lang-box {
        cursor: pointer;
        padding: 10px 16px;
        border-radius: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }
    .lang-box:hover {
        transform: scale(1.03);
    }
</style>

<div class="support-icon" id="supportIcon"></div>
<div class="chat-box" id="chatBox">
    <div class="chat-header">
        <span class="chat-header-title">Chùa tôi <i class="fas fa-circle online-icon"></i></span>
        <div class="chat-header-buttons">
            <button id="minimizeBtn"><i class="fas fa-minus"></i></button>
            <button id="expandBtn"><i class="fas fa-expand"></i></button>
            <button id="closeBtn"><i class="fas fa-times"></i></button>
        </div>
    </div>
    <div class="chat-messages" id="chatMessages">
        <button class="scroll-down-btn" id="scrollDownBtn"><i class="fas fa-arrow-down"></i></button>
    </div>
    <form class="chat-input" id="chatForm">
        <textarea name="question" id="question" placeholder="Nhập câu hỏi..." required></textarea>
        <button type="submit"></button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let selectedLanguage = '';
    let pendingQuestion = '';
    const supportIcon = document.getElementById('supportIcon');
    const chatBox = document.getElementById('chatBox');
    const chatForm = document.getElementById('chatForm');
    const questionInput = document.getElementById('question');
    const chatMessages = document.getElementById('chatMessages');
    const scrollDownBtn = document.getElementById('scrollDownBtn');
    const closeBtn = document.getElementById('closeBtn');
    const expandBtn = document.getElementById('expandBtn');
    const minimizeBtn = document.getElementById('minimizeBtn');
    let isFirstOpen = true;
    let isExpanded = false;

    supportIcon.addEventListener('click', () => {
        chatBox.classList.toggle('show');
        if (isFirstOpen && chatBox.classList.contains('show')) {
            const botGreeting = 'Xin chào, tôi là chatbot, rất vui khi được trò chuyện với bạn.';
            appendMessage('ai', botGreeting);
            showLanguageSelection();
            scrollToBottom();
            isFirstOpen = false;
        }
    });

    closeBtn.addEventListener('click', () => {
        chatBox.classList.remove('show');
    });

    expandBtn.addEventListener('click', () => {
        if (!isExpanded) {
            chatBox.classList.add('expanded');
            isExpanded = true;
        }
    });

    minimizeBtn.addEventListener('click', () => {
        if (isExpanded) {
            chatBox.classList.remove('expanded');
            isExpanded = false;
        }
    });

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        sendMessage();
    });

    questionInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    chatMessages.addEventListener('scroll', () => {
        const isAtBottom = chatMessages.scrollTop + chatMessages.clientHeight >= chatMessages.scrollHeight - 10;
        scrollDownBtn.style.display = isAtBottom ? 'none' : 'flex';
    });

    scrollDownBtn.addEventListener('click', () => {
        scrollToBottom();
    });

    function containsVietnamese(text) {
        const regex = /[áàảãạăắằẳẵặâấầẩẫậđéèẻẽẹêếềểễệíìỉĩịóòỏõọôốồổỗộơớờởỡợúùủũụưứừửữựýỳỷỹỵ]/i;
        return regex.test(text);
    }

    async function sendMessage() {
        const question = questionInput.value.trim();
        if (!question) return;

        if (selectedLanguage === 'English' && containsVietnamese(question)) {
            appendMessage('error', 'Bạn đã chọn English. Vui lòng đặt câu hỏi bằng tiếng English.');
            pendingQuestion = question;
            questionInput.value = '';
            selectedLanguage = '';
            showLanguageSelection();
            return;
        }
        if (selectedLanguage === 'Vietnamese' && !containsVietnamese(question)) {
            appendMessage('error', 'Bạn đã chọn Tiếng Việt. Vui lòng đặt câu hỏi bằng Tiếng Việt.');
            pendingQuestion = question;
            questionInput.value = '';
            selectedLanguage = '';
            showLanguageSelection();
            return;
        }
        appendMessage('user', question);
        questionInput.value = '';
        scrollToBottom();

        const formData = new FormData();
        formData.append('action', 'send_message');
        formData.append('question', question);
        formData.append('selectedLang', selectedLanguage);

        try {
            // Gọi đúng file xử lý là chat.php
            const response = await fetch('./chat.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            appendMessage(data.status === 'success' ? 'ai' : 'error', data.message);
            scrollToBottom();
        } catch (error) {
            appendMessage('error', 'Lỗi kết nối!');
            scrollToBottom();
            console.error(error);
        }
    }

    function appendMessage(sender, message) {
        const div = document.createElement('div');
        div.className = `message ${sender}`;
        let innerHTML = `<div class="message-bubble">${message}`;
        if (sender === 'ai') {
            innerHTML += `<div class="rating-icons">
                            <i class="fas fa-thumbs-up" data-rating="like"></i>
                            <i class="fas fa-thumbs-down" data-rating="dislike"></i>
                          </div>`;
        }
        innerHTML += `</div>`;
        div.innerHTML = innerHTML;
        chatMessages.appendChild(div);
    }

    function scrollToBottom() {
        chatMessages.scrollTo({
            top: chatMessages.scrollHeight,
            behavior: 'smooth'
        });
        scrollDownBtn.style.display = 'none';
    }

    function showLanguageSelection() {
        if(document.getElementById('langSelect')) return;
        const langDiv = document.createElement('div');
        langDiv.className = 'lang-select';
        langDiv.id = 'langSelect';

        const vietnameseBox = document.createElement('div');
        vietnameseBox.className = 'lang-box';
        vietnameseBox.style.backgroundColor = "#fff";
        vietnameseBox.style.color = "#333";
        vietnameseBox.style.borderBottomLeftRadius = "4px";
        vietnameseBox.innerText = "Tiếng Việt";

        const englishBox = document.createElement('div');
        englishBox.className = 'lang-box';
        englishBox.style.backgroundColor = "#DCF8C6";
        englishBox.style.color = "#333";
        englishBox.style.borderBottomRightRadius = "4px";
        englishBox.innerText = "English";

        langDiv.appendChild(vietnameseBox);
        langDiv.appendChild(englishBox);
        chatMessages.appendChild(langDiv);

        vietnameseBox.addEventListener('click', () => {
            selectedLanguage = 'Vietnamese';
            removeLanguageSelection();
            appendMessage('ai', 'Bạn đã chọn Tiếng Việt. Từ giờ, các câu trả lời sẽ bằng Tiếng Việt.');
            scrollToBottom();
            if(pendingQuestion !== ''){
                questionInput.value = pendingQuestion;
                pendingQuestion = '';
                sendMessage();
            }
        });
        englishBox.addEventListener('click', () => {
            selectedLanguage = 'English';
            removeLanguageSelection();
            appendMessage('ai', 'You have selected English. From now on, all responses will be in English.');
            scrollToBottom();
            if(pendingQuestion !== ''){
                questionInput.value = pendingQuestion;
                pendingQuestion = '';
                sendMessage();
            }
        });
    }

    function removeLanguageSelection() {
        const langDiv = document.getElementById('langSelect');
        if (langDiv) {
            langDiv.remove();
        }
    }

    chatMessages.addEventListener('click', (e) => {
        if (e.target && e.target.matches('.rating-icons i')) {
            const rating = e.target.getAttribute('data-rating');
            const messageBubble = e.target.closest('.message-bubble');
            const answer = messageBubble ? messageBubble.innerText.split('\n')[0] : '';
            if (!answer) return;
            e.target.style.animation = 'pop 0.3s ease';
            setTimeout(() => {
                e.target.style.animation = '';
            }, 300);
            saveRating(answer, rating);
            e.target.style.color = '#52C0E1';
        }
    });

    function saveRating(answer, rating) {
        const formData = new FormData();
        formData.append('action', 'save_rating');
        formData.append('answer', answer);
        formData.append('rating', rating);
        fetch('./chat.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status !== 'success') {
                console.error('Lưu đánh giá lỗi:', data.message);
            }
        })
        .catch(error => console.error('Lỗi kết nối khi lưu đánh giá:', error));
    }
});
</script>
