// ================= DATA INTI & DATABASE LOCALSTORAGE =================
const DEFAULT_SERVICES = [
    {
        id: 1,
        nama: "Akta Jual Beli (AJB)",
        deskripsi: "Akta autentik yang membuktikan adanya peralihan hak atas tanah dan bangunan karena jual beli.",
        estimasi: "7-14 Hari Kerja",
        syarat: [
            "Fotokopi KTP Suami & Istri (Penjual & Pembeli)",
            "Fotokopi Kartu Keluarga (KK) Penjual & Pembeli",
            "Fotokopi Akta Nikah (Jika sudah menikah)",
            "Sertifikat Tanah Asli (SHM / SHGB) untuk pengecekan",
            "Surat Pemberitahuan Pajak Terhutang (SPPT) PBB 5 tahun terakhir + STTS (bukti bayar)",
            "Fotokopi NPWP Penjual & Pembeli"
        ]
    },
    {
        id: 2,
        nama: "Pendirian PT / CV",
        deskripsi: "Akta pendirian badan hukum perseroan terbatas atau persekutuan komanditer beserta SK Kemenkumham.",
        estimasi: "5-10 Hari Kerja",
        syarat: [
            "Fotokopi KTP para pendiri (minimal 2 orang)",
            "Fotokopi NPWP para pendiri",
            "Pernyataan domisili usaha",
            "Struktur kepengurusan (Direktur & Komisaris)",
            "Rincian modal dasar & modal disetor",
            "3 pilihan nama PT/CV (terdiri dari 3 kata)"
        ]
    },
    {
        id: 3,
        nama: "Akta Hibah",
        deskripsi: "Akta yang membuktikan pemberian hak atas barang/tanah secara cuma-cuma dari pemberi hibah kepada penerima hibah.",
        estimasi: "5-10 Hari Kerja",
        syarat: [
            "Sertifikat Tanah Asli",
            "Fotokopi KTP & KK Pemberi Hibah & Penerima Hibah",
            "Surat Persetujuan dari Anak Kandung / Ahli Waris",
            "PBB Terakhir",
            "Surat Keterangan Kematian (jika ada pemberi hibah terdahulu yang meninggal)"
        ]
    },
    {
        id: 4,
        nama: "Legalisasi Dokumen",
        deskripsi: "Mengesahkan tanda tangan para pihak yang dibuat di bawah tangan setelah dicocokkan dengan dokumen asli.",
        estimasi: "1 Hari Kerja",
        syarat: [
            "Dokumen Asli yang akan dilegalisasi",
            "Fotokopi KTP pemilik dokumen / penandatangan",
            "Hadir langsung di hadapan Notaris untuk tanda tangan"
        ]
    },
    {
        id: 5,
        nama: "Surat Kuasa",
        deskripsi: "Surat pemberian kuasa formal dari satu pihak ke pihak lain untuk pengurusan tertentu.",
        estimasi: "2-4 Hari Kerja",
        syarat: [
            "KTP Pemberi Kuasa & Penerima Kuasa",
            "Keterangan/dokumen objek yang dikuasakan (misal sertifikat, buku tabungan, BPKB)",
            "Kehadiran pemberi kuasa"
        ]
    },
    {
        id: 6,
        nama: "Surat Pernyataan",
        deskripsi: "Pernyataan resmi bermeterai mengenai suatu hal atau kondisi yang diakui kebenarannya demi hukum.",
        estimasi: "1-2 Hari Kerja",
        syarat: [
            "KTP Pembuat Pernyataan",
            "Meterai Rp 10.000",
            "Bukti pendukung mengenai hal yang dideklarasikan"
        ]
    }
];

// Inisialisasi Database Lokal
function initDatabase() {
    if (!localStorage.getItem("db_initialized")) {
        // Data Akun Awal
        const users = [
            { id_user: 1, username: "notaris", password: "123", nama: "Eka Sulistya, S.H., M.Kn.", role: "notaris" },
            { id_user: 2, username: "alya", password: "123", nama: "Putri Alya Fadhilah", role: "client" },
            { id_user: 3, username: "budi", password: "123", nama: "Budi Setiawan", role: "client" }
        ];

        // Data Biodata Client
        const clients = [
            { id_client: 1, id_user: 2, nama: "Putri Alya Fadhilah", nik: "3202316139", no_hp: "08123456789", email: "alya@gmail.com", alamat: "Jl. Pangeran Natakusuma No. 45, Pontianak", created_at: "2026-06-10 10:00:00" },
            { id_client: 2, id_user: 3, nama: "Budi Setiawan", nik: "3202316140", no_hp: "081299998888", email: "budi@yahoo.com", alamat: "Jl. Ahmad Yani No. 12, Pontianak", created_at: "2026-07-01 11:30:00" }
        ];

        // Data Permintaan Layanan
        const permintaan = [
            { 
                id_permintaan: 101, 
                id_client: 1, 
                id_layanan: 2, 
                tanggal_permintaan: "2026-07-12", 
                status: "Diproses", 
                keterangan: "Draf pendirian PT sedang disusun oleh staf Notaris. Menunggu jadwal tanda tangan.",
                berkas: ["KTP_Alya.pdf", "NPWP_Alya.pdf", "Rencana_PT_Maju.pdf"],
                created_at: "2026-07-12 09:15:00"
            },
            { 
                id_permintaan: 102, 
                id_client: 2, 
                id_layanan: 1, 
                tanggal_permintaan: "2026-07-05", 
                status: "Selesai", 
                keterangan: "Akta Jual Beli telah ditandatangani dan diserahkan kepada pembeli.",
                berkas: ["KTP_Budi.pdf", "Sertifikat_SHM.pdf", "PBB_2025.pdf"],
                created_at: "2026-07-05 14:00:00"
            },
            { 
                id_permintaan: 103, 
                id_client: 1, 
                id_layanan: 4, 
                tanggal_permintaan: "2026-07-15", 
                status: "Menunggu", 
                keterangan: "Menunggu pemeriksaan kelengkapan berkas asli oleh Notaris.",
                berkas: ["Ijazah_Asli.pdf", "Transkrip.pdf"],
                created_at: "2026-07-15 08:30:00"
            }
        ];

        // Data Akta
        const akta = [
            { id_akta: 501, id_permintaan: 102, nomor_akta: "45/Notaris/2026", nama_akta: "Akta Jual Beli Tanah Budi & Roni", tanggal_akta: "2026-07-10", file_akta: "AJB_Budi_Roni_Signed.pdf" }
        ];

        // Data Surat
        const surat = [
            { id_surat: 601, id_permintaan: 101, nomor_surat: "189/SK-ES/VII/2026", jenis_surat: "Surat Keterangan Pengurusan PT", tanggal_surat: "2026-07-13", file_surat: "Surat_Ket_PT_Alya.pdf", keterangan: "Surat keterangan sementara untuk pembukaan rekening bank." }
        ];

        // Data Buku Tamu Kunjungan
        const bukuTamu = [
            { id_tamu: 1, id_client: 2, nama_tamu: "Budi Setiawan", instansi: "PT. Sentosa Baru", nomor_hp: "081299998888", keperluan: "Menyerahkan sertifikat tanah asli untuk AJB", tanggal_kunjungan: "2026-07-05 09:30" },
            { id_tamu: 2, id_client: 2, nama_tamu: "Budi Setiawan", instansi: "PT. Sentosa Baru", nomor_hp: "081299998888", keperluan: "Penandatanganan Minuta Akta Jual Beli", tanggal_kunjungan: "2026-07-10 11:00" },
            { id_tamu: 3, id_client: null, nama_tamu: "Hendra Wijaya", instansi: "Pribadi", nomor_hp: "085388112233", keperluan: "Konsultasi waris dan pembagian hak tanah", tanggal_kunjungan: "2026-07-14 13:15" },
            { id_tamu: 4, id_client: 1, nama_tamu: "Putri Alya Fadhilah", instansi: "Politeknik Negeri Pontianak", nomor_hp: "08123456789", keperluan: "Konsultasi akta pendirian CV usaha mahasiswa", tanggal_kunjungan: "2026-07-16 10:00" }
        ];

        localStorage.setItem("db_users", JSON.stringify(users));
        localStorage.setItem("db_clients", JSON.stringify(clients));
        localStorage.setItem("db_permintaan", JSON.stringify(permintaan));
        localStorage.setItem("db_akta", JSON.stringify(akta));
        localStorage.setItem("db_surat", JSON.stringify(surat));
        localStorage.setItem("db_buku_tamu", JSON.stringify(bukuTamu));
        localStorage.setItem("db_initialized", "true");
    }
}

// Helper Get Data
const getData = (key) => JSON.parse(localStorage.getItem(key)) || [];
const setData = (key, data) => localStorage.setItem(key, JSON.stringify(data));

// Session data
let currentUserSession = null;

// ================= ROUTING & VIEW CONTROLLER =================
function showPublicPage(pageId) {
    // Hide panels
    document.getElementById("public-interface").style.display = "block";
    document.getElementById("client-panel").style.display = "none";
    document.getElementById("admin-panel").style.display = "none";

    // Switch public view active
    document.querySelectorAll("#public-interface .page-view").forEach(page => {
        page.classList.remove("active");
    });
    const targetPage = document.getElementById(`page-${pageId}`);
    if (targetPage) {
        targetPage.classList.add("active");
    }

    // Nav-link active state
    document.querySelectorAll(".nav-links a").forEach(link => {
        link.classList.remove("active");
    });
    const activeNav = document.getElementById(`nav-${pageId}`);
    if (activeNav) {
        activeNav.classList.add("active");
    }

    // Scroll to top
    window.scrollTo(0, 0);
}

function showClientSection(secId) {
    document.querySelectorAll(".client-section").forEach(sec => {
        sec.classList.remove("active");
        sec.style.display = "none";
    });
    const targetSec = document.getElementById(`csec-${secId}`);
    if (targetSec) {
        targetSec.classList.add("active");
        targetSec.style.display = "block";
    }

    // Sidebar nav active state
    document.querySelectorAll(".sidebar-nav li").forEach(li => {
        li.classList.remove("active");
    });
    const activeLi = document.getElementById(`cnav-${secId}`);
    if (activeLi) {
        activeLi.classList.add("active");
    }
    
    if (secId === 'dashboard') renderClientDashboard();
    if (secId === 'persyaratan') renderClientPersyaratan();
    if (secId === 'biodata') loadClientBiodataForm();
    if (secId === 'bukutamu') renderClientGuestbook();
}

function showAdminSection(secId) {
    document.querySelectorAll(".admin-section").forEach(sec => {
        sec.classList.remove("active");
        sec.style.display = "none";
    });
    const targetSec = document.getElementById(`asec-${secId}`);
    if (targetSec) {
        targetSec.classList.add("active");
        targetSec.style.display = "block";
    }

    // Sidebar nav active
    document.querySelectorAll("#admin-panel .sidebar-nav li").forEach(li => {
        li.classList.remove("active");
    });
    const activeLi = document.getElementById(`anav-${secId}`);
    if (activeLi) {
        activeLi.classList.add("active");
    }

    if (secId === 'dashboard') renderAdminDashboard();
    if (secId === 'clients') renderAdminClients();
    if (secId === 'requests') renderAdminRequests();
    if (secId === 'documents') renderAdminDocuments();
    if (secId === 'bukutamu') renderAdminGuestbook();
}

// Theme Toggle Functionality
function toggleTheme() {
    const html = document.documentElement;
    const icon = document.getElementById("theme-icon");
    const currentTheme = html.getAttribute("data-theme");
    
    if (currentTheme === "dark") {
        html.setAttribute("data-theme", "light");
        icon.className = "fa-solid fa-moon";
    } else {
        html.setAttribute("data-theme", "dark");
        icon.className = "fa-solid fa-sun";
    }
}

// Notification helper
function showToast(message, isError = false) {
    const toast = document.getElementById("toast");
    const icon = document.getElementById("toast-icon");
    const text = document.getElementById("toast-message");
    
    text.innerText = message;
    if (isError) {
        toast.style.borderLeftColor = "var(--danger)";
        icon.className = "fa-solid fa-circle-exclamation text-gold";
        icon.style.color = "var(--danger)";
    } else {
        toast.style.borderLeftColor = "var(--accent-gold)";
        icon.className = "fa-solid fa-circle-check text-gold";
        icon.style.color = "var(--accent-gold)";
    }
    
    toast.style.display = "flex";
    
    setTimeout(() => {
        toast.style.display = "none";
    }, 4000);
}

// Modal closing helper
function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = "flex";
}


// ================= AUTH CONTROLLER =================
function handleLogin(event) {
    event.preventDefault();
    const userVal = document.getElementById("login-username").value.trim().toLowerCase();
    const passVal = document.getElementById("login-password").value;
    const roleVal = document.getElementById("login-role").value;

    const users = getData("db_users");
    const foundUser = users.find(u => u.username === userVal && u.password === passVal && u.role === roleVal);

    if (foundUser) {
        currentUserSession = foundUser;
        showToast(`Selamat datang kembali, ${foundUser.nama}!`);
        
        // Redirect based on role
        if (foundUser.role === "notaris") {
            document.getElementById("public-interface").style.display = "none";
            document.getElementById("admin-panel").style.display = "block";
            showAdminSection('dashboard');
        } else if (foundUser.role === "client") {
            const clients = getData("db_clients");
            const myProfile = clients.find(c => c.id_user === foundUser.id_user);
            currentUserSession.clientProfile = myProfile;
            
            document.getElementById("public-interface").style.display = "none";
            document.getElementById("client-panel").style.display = "block";
            document.getElementById("client-display-name").innerText = foundUser.nama;
            showClientSection('dashboard');
        }
        
        // Reset login form
        document.getElementById("form-login").reset();
    } else {
        showToast("Username, Password, atau Hak Akses tidak cocok!", true);
    }
}

function handleRegister(event) {
    event.preventDefault();
    const nama = document.getElementById("reg-nama").value.trim();
    const nik = document.getElementById("reg-nik").value.trim();
    const nohp = document.getElementById("reg-nohp").value.trim();
    const email = document.getElementById("reg-email").value.trim();
    const alamat = document.getElementById("reg-alamat").value.trim();
    const username = document.getElementById("reg-username").value.trim().toLowerCase();
    const password = document.getElementById("reg-password").value;

    const users = getData("db_users");
    const clients = getData("db_clients");

    // Check availability
    if (users.some(u => u.username === username)) {
        showToast("Username sudah digunakan!", true);
        return;
    }
    if (clients.some(c => c.nik === nik)) {
        showToast("NIK KTP sudah terdaftar!", true);
        return;
    }

    // Insert user
    const newUserId = users.length > 0 ? Math.max(...users.map(u => u.id_user)) + 1 : 1;
    const newUser = { id_user: newUserId, username, password, nama, role: "client" };
    users.push(newUser);
    setData("db_users", users);

    // Insert client profile
    const newClientId = clients.length > 0 ? Math.max(...clients.map(c => c.id_client)) + 1 : 1;
    const now = new Date();
    const dateStr = now.toISOString().slice(0, 19).replace('T', ' ');
    const newClient = { id_client: newClientId, id_user: newUserId, nama, nik, no_hp: nohp, email, alamat, created_at: dateStr };
    clients.push(newClient);
    setData("db_clients", clients);

    showToast("Pendaftaran akun berhasil! Silakan masuk.");
    
    // Auto login
    currentUserSession = newUser;
    currentUserSession.clientProfile = newClient;
    
    document.getElementById("public-interface").style.display = "none";
    document.getElementById("client-panel").style.display = "block";
    document.getElementById("client-display-name").innerText = newUser.nama;
    document.getElementById("form-register").reset();
    showClientSection('dashboard');
}

function handleLogout() {
    currentUserSession = null;
    showToast("Anda telah keluar dari sistem.");
    showPublicPage('home');
}


// ================= CLIENT PANEL FUNCTIONS =================
function renderClientDashboard() {
    const idClient = currentUserSession.clientProfile.id_client;
    const allRequests = getData("db_permintaan");
    
    // Filter client's requests
    const clientRequests = allRequests.filter(r => r.id_client === idClient);
    
    // Render Stats
    document.getElementById("stat-client-total").innerText = clientRequests.length;
    document.getElementById("stat-client-process").innerText = clientRequests.filter(r => r.status === "Diproses" || r.status === "Menunggu").length;
    document.getElementById("stat-client-done").innerText = clientRequests.filter(r => r.status === "Selesai").length;
    
    // Render Table
    const tableBody = document.getElementById("client-orders-table");
    tableBody.innerHTML = "";
    
    if (clientRequests.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: var(--text-muted);">Belum ada riwayat pengajuan layanan.</td></tr>`;
        return;
    }
    
    // Sort desc by id_permintaan
    clientRequests.sort((a, b) => b.id_permintaan - a.id_permintaan);
    
    clientRequests.forEach(req => {
        const svc = DEFAULT_SERVICES.find(s => s.id === req.id_layanan);
        let badgeClass = "badge-pending";
        if (req.status === "Diproses") badgeClass = "badge-process";
        if (req.status === "Selesai") badgeClass = "badge-success";
        if (req.status === "Ditolak") badgeClass = "badge-danger";
        
        let fileLinks = req.berkas.map(f => `<a href="#" onclick="alert('Simulasi download file: ${f}')" style="color: var(--accent-gold); display: block; font-size: 12px;"><i class="fa-solid fa-file-pdf"></i> ${f}</a>`).join('');
        
        tableBody.innerHTML += `
            <tr>
                <td><strong>#${req.id_permintaan}</strong></td>
                <td>${svc ? svc.nama : 'Layanan'}</td>
                <td>${req.tanggal_permintaan}</td>
                <td><span class="badge ${badgeClass}">${req.status}</span></td>
                <td><span style="font-size: 13px;">${req.keterangan || '-'}</span></td>
                <td>${fileLinks}</td>
            </tr>
        `;
    });
}

function loadApplyFormServices() {
    const select = document.getElementById("apply-layanan");
    select.innerHTML = '<option value="" disabled selected>-- Pilih Layanan --</option>';
    DEFAULT_SERVICES.forEach(s => {
        select.innerHTML += `<option value="${s.id}">${s.nama} (${s.estimasi})</option>`;
    });
}

function showApplyRequirements() {
    const select = document.getElementById("apply-layanan");
    const svcId = parseInt(select.value);
    const box = document.getElementById("apply-requirements-box");
    const list = document.getElementById("apply-requirements-list");
    
    const svc = DEFAULT_SERVICES.find(s => s.id === svcId);
    if (svc) {
        box.style.display = "block";
        list.innerHTML = "";
        svc.syarat.forEach(s => {
            list.innerHTML += `<li>${s}</li>`;
        });
    } else {
        box.style.display = "none";
    }
}

function handleApplyService(event) {
    event.preventDefault();
    const svcId = parseInt(document.getElementById("apply-layanan").value);
    const catatan = document.getElementById("apply-catatan").value.trim();
    const fileInput = document.getElementById("apply-files");
    const clientProfile = currentUserSession.clientProfile;
    
    if (!svcId) {
        showToast("Silakan pilih jenis layanan terlebih dahulu!", true);
        return;
    }
    
    // Simulate filename array
    let files = [];
    for (let i = 0; i < fileInput.files.length; i++) {
        files.push(fileInput.files[i].name);
    }
    
    if (files.length === 0) {
        showToast("Harap unggah minimal 1 berkas pendukung persyaratan!", true);
        return;
    }
    
    const allRequests = getData("db_permintaan");
    const newReqId = allRequests.length > 0 ? Math.max(...allRequests.map(r => r.id_permintaan)) + 1 : 101;
    const now = new Date();
    const dateStr = now.toISOString().slice(0, 10);
    const timeStr = now.toISOString().slice(0, 19).replace('T', ' ');
    
    const newReq = {
        id_permintaan: newReqId,
        id_client: clientProfile.id_client,
        id_layanan: svcId,
        tanggal_permintaan: dateStr,
        status: "Menunggu",
        keterangan: "Menunggu verifikasi kelengkapan berkas oleh Admin Notaris.",
        berkas: files,
        created_at: timeStr
    };
    
    allRequests.push(newReq);
    setData("db_permintaan", allRequests);
    
    showToast(`Pengajuan #${newReqId} berhasil dikirim!`);
    document.getElementById("form-apply-service").reset();
    document.getElementById("apply-requirements-box").style.display = "none";
    showClientSection('dashboard');
}

function renderClientPersyaratan() {
    const grid = document.getElementById("client-persyaratan-grid");
    grid.innerHTML = "";
    
    DEFAULT_SERVICES.forEach(s => {
        let requirementsHtml = s.syarat.map(item => `<li><i class="fa-solid fa-check text-gold" style="font-size: 11px; margin-right: 5px;"></i> ${item}</li>`).join('');
        
        grid.innerHTML += `
            <div class="service-card">
                <div class="service-icon"><i class="fa-solid fa-folder-open"></i></div>
                <h4>${s.nama}</h4>
                <p>${s.deskripsi}</p>
                <div style="margin-top: 15px; margin-bottom: 15px; text-align: left;">
                    <strong style="font-size: 13px; color: var(--accent-gold);">Dokumen Persyaratan:</strong>
                    <ul style="list-style: none; font-size: 12px; margin-top: 8px; color: var(--text-secondary); display: flex; flex-direction: column; gap: 5px;">
                        ${requirementsHtml}
                    </ul>
                </div>
                <div class="service-meta">
                    <span>Estimasi: <strong>${s.estimasi}</strong></span>
                </div>
            </div>
        `;
    });
}

function loadClientBiodataForm() {
    const client = currentUserSession.clientProfile;
    document.getElementById("bio-nama").value = client.nama;
    document.getElementById("bio-nik").value = client.nik;
    document.getElementById("bio-nohp").value = client.no_hp;
    document.getElementById("bio-email").value = client.email;
    document.getElementById("bio-alamat").value = client.alamat;
    document.getElementById("bio-password").value = "";
}

function handleSaveBiodata(event) {
    event.preventDefault();
    const nama = document.getElementById("bio-nama").value.trim();
    const nohp = document.getElementById("bio-nohp").value.trim();
    const email = document.getElementById("bio-email").value.trim();
    const alamat = document.getElementById("bio-alamat").value.trim();
    const pass = document.getElementById("bio-password").value;
    
    const clients = getData("db_clients");
    const users = getData("db_users");
    
    // Find index
    const clientIdx = clients.findIndex(c => c.id_client === currentUserSession.clientProfile.id_client);
    const userIdx = users.findIndex(u => u.id_user === currentUserSession.id_user);
    
    if (clientIdx !== -1) {
        clients[clientIdx].nama = nama;
        clients[clientIdx].no_hp = nohp;
        clients[clientIdx].email = email;
        clients[clientIdx].alamat = alamat;
        setData("db_clients", clients);
        currentUserSession.clientProfile = clients[clientIdx];
    }
    
    if (userIdx !== -1) {
        users[userIdx].nama = nama;
        if (pass !== "") {
            users[userIdx].password = pass;
        }
        setData("db_users", users);
        currentUserSession.nama = nama;
        document.getElementById("client-display-name").innerText = nama;
    }
    
    showToast("Biodata profil berhasil diperbarui!");
    showClientSection('dashboard');
}

function renderClientGuestbook() {
    const table = document.getElementById("client-guestbook-table");
    table.innerHTML = "";
    
    const allGuests = getData("db_buku_tamu");
    const myGuests = allGuests.filter(g => g.id_client === currentUserSession.clientProfile.id_client);
    
    if (myGuests.length === 0) {
        table.innerHTML = `<tr><td colspan="4" style="text-align: center; color: var(--text-muted);">Anda belum pernah melakukan pencatatan kunjungan fisik.</td></tr>`;
        return;
    }
    
    // Sort by Date Desc
    myGuests.sort((a,b) => new Date(b.tanggal_kunjungan) - new Date(a.tanggal_kunjungan));
    
    myGuests.forEach((g, index) => {
        table.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${g.instansi}</td>
                <td>${g.keperluan}</td>
                <td>${g.tanggal_kunjungan} WIB</td>
            </tr>
        `;
    });
}


// ================= ADMIN/NOTARIS PANEL FUNCTIONS =================
function renderAdminDashboard() {
    const clients = getData("db_clients");
    const requests = getData("db_permintaan");
    const guestbook = getData("db_buku_tamu");
    const akta = getData("db_akta");
    
    document.getElementById("stat-admin-clients").innerText = clients.length;
    document.getElementById("stat-admin-pending").innerText = requests.filter(r => r.status === "Menunggu").length;
    document.getElementById("stat-admin-akta").innerText = akta.length;
    document.getElementById("stat-admin-guests").innerText = guestbook.length;
    
    // Render recent pending requests
    const table = document.getElementById("admin-recent-requests-table");
    table.innerHTML = "";
    
    const pendingReqs = requests.filter(r => r.status === "Menunggu");
    if (pendingReqs.length === 0) {
        table.innerHTML = `<tr><td colspan="6" style="text-align: center; color: var(--text-muted);">Tidak ada permohonan baru yang tertunda.</td></tr>`;
        return;
    }
    
    pendingReqs.sort((a, b) => b.id_permintaan - a.id_permintaan);
    
    pendingReqs.forEach(req => {
        const client = clients.find(c => c.id_client === req.id_client);
        const svc = DEFAULT_SERVICES.find(s => s.id === req.id_layanan);
        
        table.innerHTML += `
            <tr>
                <td><strong>#${req.id_permintaan}</strong></td>
                <td>${client ? client.nama : 'Client Terhapus'}</td>
                <td>${svc ? svc.nama : 'Layanan'}</td>
                <td>${req.tanggal_permintaan}</td>
                <td><span class="badge badge-pending">${req.status}</span></td>
                <td>
                    <button class="btn btn-primary" style="padding: 4px 10px; font-size: 11px;" onclick="openProcessRequestModal(${req.id_permintaan})">Proses</button>
                </td>
            </tr>
        `;
    });
}

function renderAdminClients() {
    const clients = getData("db_clients");
    const table = document.getElementById("admin-clients-table");
    table.innerHTML = "";
    
    if (clients.length === 0) {
        table.innerHTML = `<tr><td colspan="6" style="text-align: center; color: var(--text-muted);">Belum ada data client terdaftar.</td></tr>`;
        return;
    }
    
    clients.forEach(c => {
        table.innerHTML += `
            <tr class="client-row" data-name="${c.nama.toLowerCase()}">
                <td><strong>${c.nama}</strong></td>
                <td>${c.nik}</td>
                <td>${c.no_hp}</td>
                <td>${c.email}</td>
                <td>${c.alamat}</td>
                <td>
                    <button class="btn-icon" onclick="openEditClientModal(${c.id_client})" title="Edit Client"><i class="fa-solid fa-edit"></i></button>
                    <button class="btn-icon delete" onclick="deleteClient(${c.id_client})" title="Hapus Client"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `;
    });
}

function filterClientTable() {
    const q = document.getElementById("admin-client-search").value.toLowerCase();
    document.querySelectorAll(".client-row").forEach(row => {
        const name = row.getAttribute("data-name");
        row.style.display = name.includes(q) ? "" : "none";
    });
}

function openAddClientModal() {
    document.getElementById("form-admin-client").reset();
    document.getElementById("ac-id").value = "";
    document.getElementById("modal-client-title").innerText = "Tambah Client Baru";
    document.getElementById("ac-login-fields").style.display = "block";
    document.getElementById("ac-nik").readOnly = false;
    document.getElementById("ac-nik").style.opacity = "1";
    openModal("modal-client");
}

function openEditClientModal(idClient) {
    const clients = getData("db_clients");
    const client = clients.find(c => c.id_client === idClient);
    
    if (client) {
        document.getElementById("ac-id").value = client.id_client;
        document.getElementById("ac-nama").value = client.nama;
        document.getElementById("ac-nik").value = client.nik;
        document.getElementById("ac-nik").readOnly = true;
        document.getElementById("ac-nik").style.opacity = "0.7";
        document.getElementById("ac-nohp").value = client.no_hp;
        document.getElementById("ac-email").value = client.email;
        document.getElementById("ac-alamat").value = client.alamat;
        document.getElementById("modal-client-title").innerText = "Ubah Data Client";
        document.getElementById("ac-login-fields").style.display = "none"; // Gak usah edit login dari sini
        openModal("modal-client");
    }
}

function handleAdminClientSubmit(event) {
    event.preventDefault();
    const idClient = document.getElementById("ac-id").value;
    const nama = document.getElementById("ac-nama").value.trim();
    const nik = document.getElementById("ac-nik").value.trim();
    const nohp = document.getElementById("ac-nohp").value.trim();
    const email = document.getElementById("ac-email").value.trim();
    const alamat = document.getElementById("ac-alamat").value.trim();

    const clients = getData("db_clients");
    const users = getData("db_users");

    if (idClient === "") {
        // Mode Tambah
        const username = document.getElementById("ac-username").value.trim().toLowerCase();
        if (!username) {
            showToast("Harap isi username default client!", true);
            return;
        }
        if (users.some(u => u.username === username)) {
            showToast("Username sudah digunakan!", true);
            return;
        }
        if (clients.some(c => c.nik === nik)) {
            showToast("NIK sudah terdaftar!", true);
            return;
        }

        const newUserId = users.length > 0 ? Math.max(...users.map(u => u.id_user)) + 1 : 1;
        const newUser = { id_user: newUserId, username, password: "123", nama, role: "client" };
        users.push(newUser);
        setData("db_users", users);

        const newClientId = clients.length > 0 ? Math.max(...clients.map(c => c.id_client)) + 1 : 1;
        const now = new Date();
        const newClient = { id_client: newClientId, id_user: newUserId, nama, nik, no_hp: nohp, email, alamat, created_at: now.toISOString().slice(0, 19).replace('T', ' ') };
        clients.push(newClient);
        setData("db_clients", clients);

        showToast("Client baru & Akun Login berhasil dibuat!");
    } else {
        // Mode Edit
        const idx = clients.findIndex(c => c.id_client === parseInt(idClient));
        if (idx !== -1) {
            clients[idx].nama = nama;
            clients[idx].no_hp = nohp;
            clients[idx].email = email;
            clients[idx].alamat = alamat;
            setData("db_clients", clients);

            // Update user name as well
            const uIdx = users.findIndex(u => u.id_user === clients[idx].id_user);
            if (uIdx !== -1) {
                users[uIdx].nama = nama;
                setData("db_users", users);
            }

            showToast("Data client berhasil diperbarui.");
        }
    }

    closeModal("modal-client");
    renderAdminClients();
}

function deleteClient(idClient) {
    if (confirm("Apakah Anda yakin ingin menghapus data client beserta akun loginnya? Tindakan ini tidak dapat dibatalkan!")) {
        let clients = getData("db_clients");
        let users = getData("db_users");
        
        const client = clients.find(c => c.id_client === idClient);
        if (client) {
            clients = clients.filter(c => c.id_client !== idClient);
            users = users.filter(u => u.id_user !== client.id_user);
            
            setData("db_clients", clients);
            setData("db_users", users);
            showToast("Data client berhasil dihapus.");
            renderAdminClients();
        }
    }
}

function renderAdminRequests() {
    const requests = getData("db_permintaan");
    const clients = getData("db_clients");
    const table = document.getElementById("admin-requests-table");
    table.innerHTML = "";
    
    if (requests.length === 0) {
        table.innerHTML = `<tr><td colspan="8" style="text-align: center; color: var(--text-muted);">Belum ada permintaan layanan masuk.</td></tr>`;
        return;
    }
    
    requests.sort((a,b) => b.id_permintaan - a.id_permintaan);
    
    requests.forEach(req => {
        const client = clients.find(c => c.id_client === req.id_client);
        const svc = DEFAULT_SERVICES.find(s => s.id === req.id_layanan);
        
        let badgeClass = "badge-pending";
        if (req.status === "Diproses") badgeClass = "badge-process";
        if (req.status === "Selesai") badgeClass = "badge-success";
        if (req.status === "Ditolak") badgeClass = "badge-danger";
        
        // berkas buttons
        let berkasBtn = req.berkas.length > 0 
            ? `<button class="btn btn-outline" style="padding: 4px 8px; font-size: 11px;" onclick="viewRequestFiles(${req.id_permintaan})"><i class="fa-solid fa-paperclip"></i> ${req.berkas.length} File</button>`
            : '<span style="color: var(--text-muted); font-size: 12px;">Tanpa file</span>';
            
        table.innerHTML += `
            <tr class="req-row" data-name="${client ? client.nama.toLowerCase() : ''}" data-svc="${svc ? svc.nama.toLowerCase() : ''}" data-status="${req.status}">
                <td><strong>#${req.id_permintaan}</strong></td>
                <td>${client ? client.nama : 'Client Terhapus'}</td>
                <td>${svc ? svc.nama : 'Layanan'}</td>
                <td>${req.tanggal_permintaan}</td>
                <td><span class="badge ${badgeClass}">${req.status}</span></td>
                <td style="max-width: 200px; font-size: 13px;">${req.keterangan || '-'}</td>
                <td>${berkasBtn}</td>
                <td>
                    <button class="btn btn-primary" style="padding: 4px 10px; font-size: 11px;" onclick="openProcessRequestModal(${req.id_permintaan})">
                        <i class="fa-solid fa-cog"></i> Proses
                    </button>
                </td>
            </tr>
        `;
    });
}

function filterRequestTable() {
    const q = document.getElementById("admin-request-search").value.toLowerCase();
    const filter = document.getElementById("admin-request-filter").value;
    
    document.querySelectorAll(".req-row").forEach(row => {
        const name = row.getAttribute("data-name");
        const svc = row.getAttribute("data-svc");
        const status = row.getAttribute("data-status");
        
        const matchesQuery = name.includes(q) || svc.includes(q);
        const matchesFilter = filter === "all" || status === filter;
        
        row.style.display = (matchesQuery && matchesFilter) ? "" : "none";
    });
}

function viewRequestFiles(idReq) {
    const requests = getData("db_permintaan");
    const req = requests.find(r => r.id_permintaan === idReq);
    
    if (req) {
        const list = document.getElementById("uploaded-files-list");
        list.innerHTML = "";
        req.berkas.forEach(f => {
            list.innerHTML += `
                <li style="display: flex; justify-content: space-between; align-items: center; background: var(--bg-primary); padding: 10px 15px; border-radius: var(--border-radius); border: 1px solid var(--border-color);">
                    <span><i class="fa-solid fa-file-pdf text-gold"></i> <strong>${f}</strong></span>
                    <button class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick="alert('Simulasi download berkas client: ${f}')">Unduh</button>
                </li>
            `;
        });
        openModal("modal-view-files");
    }
}

function openProcessRequestModal(idReq) {
    const requests = getData("db_permintaan");
    const clients = getData("db_clients");
    
    const req = requests.find(r => r.id_permintaan === idReq);
    if (req) {
        const client = clients.find(c => c.id_client === req.id_client);
        const svc = DEFAULT_SERVICES.find(s => s.id === req.id_layanan);
        
        document.getElementById("pr-id").value = req.id_permintaan;
        document.getElementById("pr-client").value = client ? client.nama : 'Client Terhapus';
        document.getElementById("pr-layanan").value = svc ? svc.nama : 'Layanan';
        document.getElementById("pr-status").value = req.status;
        document.getElementById("pr-catatan").value = req.keterangan || "";
        
        openModal("modal-process-request");
    }
}

function handleProcessRequestSubmit(event) {
    event.preventDefault();
    const idReq = parseInt(document.getElementById("pr-id").value);
    const status = document.getElementById("pr-status").value;
    const catatan = document.getElementById("pr-catatan").value.trim();
    
    const requests = getData("db_permintaan");
    const reqIdx = requests.findIndex(r => r.id_permintaan === idReq);
    
    if (reqIdx !== -1) {
        requests[reqIdx].status = status;
        requests[reqIdx].keterangan = catatan;
        
        // Auto generation simulation of akta if status becomes "Selesai"
        if (status === "Selesai") {
            const akta = getData("db_akta");
            // check if akta already created for this request
            if (!akta.some(a => a.id_permintaan === idReq)) {
                const client = getData("db_clients").find(c => c.id_client === requests[reqIdx].id_client);
                const svc = DEFAULT_SERVICES.find(s => s.id === requests[reqIdx].id_layanan);
                const clientName = client ? client.nama : "Client";
                const svcName = svc ? svc.nama : "Akta";
                
                const newAktaId = akta.length > 0 ? Math.max(...akta.map(a => a.id_akta)) + 1 : 501;
                const docNo = `${newAktaId}/Notaris/${new Date().getFullYear()}`;
                
                const newAkta = {
                    id_akta: newAktaId,
                    id_permintaan: idReq,
                    nomor_akta: docNo,
                    nama_akta: `${svcName} atas nama ${clientName}`,
                    tanggal_akta: new Date().toISOString().slice(0, 10),
                    file_akta: `${svcName.replace(/\s+/g, '_')}_${clientName.replace(/\s+/g, '_')}.pdf`
                };
                
                akta.push(newAkta);
                setData("db_akta", akta);
                
                showToast(`Permintaan diselesaikan! Akta Resmi #${docNo} diterbitkan.`);
            }
        } else {
            showToast("Status permohonan berhasil diperbarui.");
        }
        
        setData("db_permintaan", requests);
    }
    
    closeModal("modal-process-request");
    renderAdminRequests();
    renderAdminDashboard();
}

function renderAdminDocuments() {
    renderAdminAkta();
    renderAdminSurat();
}

function toggleDocTab(tabName) {
    document.querySelectorAll(".doc-tab-view").forEach(tab => {
        tab.style.display = "none";
    });
    
    document.getElementById(`doc-sub-${tabName}`).style.display = "block";
    
    if (tabName === 'akta') {
        document.getElementById("btn-tab-akta").className = "btn btn-primary";
        document.getElementById("btn-tab-surat").className = "btn btn-outline";
    } else {
        document.getElementById("btn-tab-akta").className = "btn btn-outline";
        document.getElementById("btn-tab-surat").className = "btn btn-primary";
    }
}

function renderAdminAkta() {
    const akta = getData("db_akta");
    const requests = getData("db_permintaan");
    const clients = getData("db_clients");
    const table = document.getElementById("admin-akta-table");
    
    table.innerHTML = "";
    
    if (akta.length === 0) {
        table.innerHTML = `<tr><td colspan="6" style="text-align: center; color: var(--text-muted);">Belum ada arsip akta resmi.</td></tr>`;
        return;
    }
    
    akta.forEach(a => {
        const req = requests.find(r => r.id_permintaan === a.id_permintaan);
        let clientName = "-";
        if (req) {
            const c = clients.find(client => client.id_client === req.id_client);
            if (c) clientName = c.nama;
        }
        
        table.innerHTML += `
            <tr>
                <td><strong>${a.nomor_akta}</strong></td>
                <td>${a.nama_akta}</td>
                <td>${a.tanggal_akta}</td>
                <td>${clientName}</td>
                <td><a href="#" onclick="alert('Buka file akta: ${a.file_akta}')" style="color: var(--accent-gold);"><i class="fa-solid fa-file-pdf"></i> ${a.file_akta}</a></td>
                <td>
                    <button class="btn-icon delete" onclick="deleteAkta(${a.id_akta})" title="Hapus Arsip Akta"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `;
    });
}

function openAddAktaModal() {
    const select = document.getElementById("aa-client");
    select.innerHTML = '<option value="" disabled selected>-- Pilih Client --</option>';
    const clients = getData("db_clients");
    clients.forEach(c => {
        select.innerHTML += `<option value="${c.id_client}">${c.nama} - NIK: ${c.nik}</option>`;
    });
    
    document.getElementById("form-admin-akta").reset();
    openModal("modal-akta");
}

function handleAdminAktaSubmit(event) {
    event.preventDefault();
    const nomor = document.getElementById("aa-nomor").value.trim();
    const nama = document.getElementById("aa-nama").value.trim();
    const tanggal = document.getElementById("aa-tanggal").value;
    const idClient = parseInt(document.getElementById("aa-client").value);
    const fileInput = document.getElementById("aa-file");
    
    const akta = getData("db_akta");
    const requests = getData("db_permintaan");
    
    // Create virtual request to connect this manual akta to client
    const newReqId = requests.length > 0 ? Math.max(...requests.map(r => r.id_permintaan)) + 1 : 101;
    const nowStr = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const newReq = {
        id_permintaan: newReqId,
        id_client: idClient,
        id_layanan: 1, // default AJB for mockup connections
        tanggal_permintaan: tanggal,
        status: "Selesai",
        keterangan: "Penginputan akta fisik manual oleh Notaris.",
        berkas: [],
        created_at: nowStr
    };
    requests.push(newReq);
    setData("db_permintaan", requests);
    
    const newAktaId = akta.length > 0 ? Math.max(...akta.map(a => a.id_akta)) + 1 : 501;
    const newAkta = {
        id_akta: newAktaId,
        id_permintaan: newReqId,
        nomor_akta: nomor,
        nama_akta: nama,
        tanggal_akta: tanggal,
        file_akta: fileInput.files.length > 0 ? fileInput.files[0].name : "Akta_Manual.pdf"
    };
    
    akta.push(newAkta);
    setData("db_akta", akta);
    
    showToast(`Arsip Akta Baru ${nomor} berhasil diunggah!`);
    closeModal("modal-akta");
    renderAdminAkta();
}

function deleteAkta(idAkta) {
    if (confirm("Apakah Anda yakin ingin menghapus data arsip akta ini?")) {
        let akta = getData("db_akta");
        akta = akta.filter(a => a.id_akta !== idAkta);
        setData("db_akta", akta);
        showToast("Arsip akta berhasil dihapus.");
        renderAdminAkta();
    }
}

function renderAdminSurat() {
    const surat = getData("db_surat");
    const table = document.getElementById("admin-surat-table");
    table.innerHTML = "";
    
    if (surat.length === 0) {
        table.innerHTML = `<tr><td colspan="6" style="text-align: center; color: var(--text-muted);">Belum ada arsip surat keluar/masuk.</td></tr>`;
        return;
    }
    
    surat.forEach(s => {
        table.innerHTML += `
            <tr>
                <td><strong>${s.nomor_surat}</strong></td>
                <td>${s.jenis_surat}</td>
                <td>${s.tanggal_surat}</td>
                <td>${s.keterangan || '-'}</td>
                <td><a href="#" onclick="alert('Buka file surat: ${s.file_surat}')" style="color: var(--accent-gold);"><i class="fa-solid fa-file-pdf"></i> ${s.file_surat}</a></td>
                <td>
                    <button class="btn-icon delete" onclick="deleteSurat(${s.id_surat})" title="Hapus Surat"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `;
    });
}

function openAddSuratModal() {
    document.getElementById("form-admin-surat").reset();
    openModal("modal-surat");
}

function handleAdminSuratSubmit(event) {
    event.preventDefault();
    const nomor = document.getElementById("as-nomor").value.trim();
    const jenis = document.getElementById("as-jenis").value.trim();
    const tanggal = document.getElementById("as-tanggal").value;
    const keterangan = document.getElementById("as-keterangan").value.trim();
    const fileInput = document.getElementById("as-file");
    
    const surat = getData("db_surat");
    const newSuratId = surat.length > 0 ? Math.max(...surat.map(s => s.id_surat)) + 1 : 601;
    
    const newSurat = {
        id_surat: newSuratId,
        id_permintaan: null, // manual standalone document
        nomor_surat: nomor,
        jenis_surat: jenis,
        tanggal_surat: tanggal,
        file_surat: fileInput.files.length > 0 ? fileInput.files[0].name : "Surat_Manual.pdf",
        keterangan: keterangan
    };
    
    surat.push(newSurat);
    setData("db_surat", surat);
    
    showToast(`Surat #${nomor} berhasil diarsipkan!`);
    closeModal("modal-surat");
    renderAdminSurat();
}

function deleteSurat(idSurat) {
    if (confirm("Apakah Anda yakin ingin menghapus data surat ini dari arsip?")) {
        let surat = getData("db_surat");
        surat = surat.filter(s => s.id_surat !== idSurat);
        setData("db_surat", surat);
        showToast("Arsip surat berhasil dihapus.");
        renderAdminSurat();
    }
}

function renderAdminGuestbook() {
    const guestbook = getData("db_buku_tamu");
    const table = document.getElementById("admin-guestbook-table");
    table.innerHTML = "";
    
    if (guestbook.length === 0) {
        table.innerHTML = `<tr><td colspan="7" style="text-align: center; color: var(--text-muted);">Belum ada kunjungan tamu hari ini.</td></tr>`;
        return;
    }
    
    guestbook.sort((a,b) => new Date(b.tanggal_kunjungan) - new Date(a.tanggal_kunjungan));
    
    guestbook.forEach((g, index) => {
        table.innerHTML += `
            <tr class="guest-row" data-name="${g.nama_tamu.toLowerCase()}">
                <td>${index + 1}</td>
                <td><strong>${g.nama_tamu}</strong></td>
                <td>${g.instansi}</td>
                <td>${g.nomor_hp}</td>
                <td>${g.keperluan}</td>
                <td>${g.tanggal_kunjungan} WIB</td>
                <td>
                    <button class="btn-icon delete" onclick="deleteGuestLog(${g.id_tamu})" title="Hapus Log"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `;
    });
}

function filterGuestTable() {
    const q = document.getElementById("admin-guest-search").value.toLowerCase();
    document.querySelectorAll(".guest-row").forEach(row => {
        const name = row.getAttribute("data-name");
        row.style.display = name.includes(q) ? "" : "none";
    });
}

function deleteGuestLog(idTamu) {
    if (confirm("Hapus catatan kunjungan ini?")) {
        let guests = getData("db_buku_tamu");
        guests = guests.filter(g => g.id_tamu !== idTamu);
        setData("db_buku_tamu", guests);
        showToast("Log kunjungan terhapus.");
        renderAdminGuestbook();
        renderAdminDashboard();
    }
}

function clearGuestbookLogs() {
    if (confirm("Kosongkan semua log kunjungan buku tamu? Tindakan ini akan menghapus semua riwayat.")) {
        setData("db_buku_tamu", []);
        showToast("Seluruh log buku tamu dibersihkan.");
        renderAdminGuestbook();
        renderAdminDashboard();
    }
}


// ================= PUBLIC WEB GUESTBOOK AND SERVICES =================
function renderPublicServices() {
    const grid = document.getElementById("public-services-grid");
    grid.innerHTML = "";
    
    DEFAULT_SERVICES.slice(0, 3).forEach(s => { // Tampilkan 3 layanan utama di home
        grid.innerHTML += `
            <div class="service-card">
                <div class="service-icon"><i class="fa-solid fa-balance-scale"></i></div>
                <h4>${s.nama}</h4>
                <p>${s.deskripsi}</p>
                <div class="service-meta">
                    <span>Estimasi: <strong>${s.estimasi}</strong></span>
                    <a href="#" onclick="showPublicPage('services')" style="color: var(--accent-gold); text-decoration: none; font-weight: 500;">Detail <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></a>
                </div>
            </div>
        `;
    });
}

function renderDetailServices() {
    const grid = document.getElementById("detail-services-grid");
    grid.innerHTML = "";
    
    DEFAULT_SERVICES.forEach(s => {
        let reqList = s.syarat.map(item => `<li><i class="fa-solid fa-circle-check text-gold" style="font-size: 11px; margin-right: 8px;"></i> ${item}</li>`).join('');
        
        grid.innerHTML += `
            <div class="service-card" style="align-items: flex-start;">
                <div class="service-icon"><i class="fa-solid fa-file-signature"></i></div>
                <h4 style="margin-bottom: 5px;">${s.nama}</h4>
                <span class="badge badge-process" style="margin-bottom: 15px;">Waktu Proses: ${s.estimasi}</span>
                <p style="margin-bottom: 20px;">${s.deskripsi}</p>
                <div style="width: 100%; border-top: 1px solid var(--border-color); padding-top: 15px;">
                    <h5 class="font-heading text-gold" style="font-size: 15px; margin-bottom: 10px;">Berkas Yang Harus Disiapkan:</h5>
                    <ul style="list-style: none; font-size: 13px; color: var(--text-secondary); display: flex; flex-direction: column; gap: 8px;">
                        ${reqList}
                    </ul>
                </div>
            </div>
        `;
    });
}

function handleGuestbookSubmit(event) {
    event.preventDefault();
    const nama = document.getElementById("gt-nama").value.trim();
    const instansi = document.getElementById("gt-instansi").value.trim();
    const nohp = document.getElementById("gt-nohp").value.trim();
    const keperluan = document.getElementById("gt-keperluan").value.trim();
    
    const guests = getData("db_buku_tamu");
    const newGuestId = guests.length > 0 ? Math.max(...guests.map(g => g.id_tamu)) + 1 : 1;
    
    // Get client id if the client is currently logged in, else null
    let linkedClientId = null;
    if (currentUserSession && currentUserSession.role === "client") {
        linkedClientId = currentUserSession.clientProfile.id_client;
    } else {
        // match NIK/Name in database to check if registered client
        const clients = getData("db_clients");
        const found = clients.find(c => c.nama.toLowerCase() === nama.toLowerCase() || c.no_hp === nohp);
        if (found) linkedClientId = found.id_client;
    }
    
    const now = new Date();
    const dateTimeStr = now.toISOString().slice(0, 10) + " " + now.toTimeString().slice(0, 5);
    
    const newVisit = {
        id_tamu: newGuestId,
        id_client: linkedClientId,
        nama_tamu: nama,
        instansi: instansi,
        nomor_hp: nohp,
        keperluan: keperluan,
        tanggal_kunjungan: dateTimeStr
    };
    
    guests.push(newVisit);
    setData("db_buku_tamu", guests);
    
    showToast(`Terima kasih ${nama}, kunjungan Anda telah dicatat.`);
    document.getElementById("form-guestbook").reset();
    showPublicPage('home');
}


// ================= MAIN INITIALIZATION =================
document.addEventListener("DOMContentLoaded", () => {
    initDatabase();
    
    // Render public content
    renderPublicServices();
    renderDetailServices();
    
    // Load services into Select on apply form
    loadApplyFormServices();
    
    // Show public home by default
    showPublicPage('home');
});
