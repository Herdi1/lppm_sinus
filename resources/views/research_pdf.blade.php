<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal-{{ $research->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 20px;
        }

        h1,
        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>PROTEKSI ISI PROPOSAL</h1>
    <p class="center">Dilarang menyalin, menyimpan, memperbanyak sebagian atau seluruh isi proposal ini dalam bentuk
        apapun<br>kecuali oleh pengusul dan pengelola administrasi pengabdian kepada masyarakat</p>
    <h2>PROPOSAL PENELITIAN 2024</h2>
    <p class="center">Rencana Pelaksanaan Penelitian: tahun {{ $research->year }} s.d. tahun {{ $research->year }}</p>

    <h2>1. JUDUL PENELITIAN</h2>
    <p>{{ $research->title }}</p>

    <table>
        <tr>
            <th>Bidang Fokus</th>
            <th>Tema</th>
            <th>Topik (jika ada)</th>
            <th>Prioritas Riset</th>
        </tr>
        <tr>
            <td>{{ $research->researchFocus->name }}</td>
            <td>{{ $research->researchTheme->name }}</td>
            <td>{{ $research->researchTopic->name }}</td>
            <td>{{ $research->researchPriority->name }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Rumpun Ilmu Level 1</th>
            <th>Rumpun Ilmu Level 2</th>
            <th>Rumpun Ilmu Level 3</th>
        </tr>
        <tr>
            <td>{{ $research->scienceCluster1->name }}</td>
            <td>{{ $research->scienceCluster2->name }}</td>
            <td>{{ $research->scienceCluster3->name }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Skema Penelitian</th>
            <th>Strata (Dasar/Terapan/Pengembangan)</th>
            <th>Nilai SBK</th>
            <th>Target Akhir TKT</th>
            <th>Lama Kegiatan</th>
        </tr>
        <tr>
            <td>{{ $research->scheme->name }}</td>
            <td>Riset Dasar</td>
            <td>{{ $research->approval_funds }}</td>
            <td>{{ $research->tkt_final }}</td>
            <td>{{ $research->duration }} Tahun</td>
        </tr>
    </table>

    <h2>2. IDENTITAS PENGUSUL</h2>
    <table>
        <tr>
            <th>Nama, Peran</th>
            <th>Jenis</th>
            <th>Program Studi/Bagian</th>
            <th>Bidang Tugas</th>
            <th>ID Sinta</th>
        </tr>
        <tr>
            <td>{{ $research->user->name }}<br>Ketua Pengusul<br>STMIK Sinar Nusantara</td>
            <td>Dosen</td>
            <td>{{ $research->user->prodi_id }}</td>
            <td>{{ $research->leader_task }}</td>
            <td><a href="https://sinta.kemdikbud.go.id/author/6049857" target="_blank">6049857</a></td>
        </tr>
        @foreach ($research['members'] as $member)
            <tr>
                <td>{{ $member->name }}<br>{{ $member->pivot->research_roles }}<br>STMIK Sinar Nusantara</td>
                <td>Dosen</td>
                <td>{{ $member->prodi_id }}</td>
                <td>{{ $member->pivot->task }}</td>
                <td><a href="https://sinta.kemdikbud.go.id/author/5979352" target="_blank">5979352</a></td>
            </tr>
        @endforeach
    </table>

    <h2>3. MITRA KERJASAMA PENELITIAN (Jika Ada)</h2>
    <p>Pelaksanaan penelitian dapat melibatkan mitra kerjasama yaitu mitra kerjasama dalam melaksanakan penelitian,
        mitra sebagai calon pengguna hasil penelitian, atau mitra investor</p>
    <table>
        <tr>
            <th>Mitra</th>
            <th>Nama Mitra</th>
            <th>Dana</th>
        </tr>
        @foreach ($research['supportingDocument'] as $supportingDocument)
            <tr>
                <td>{{ $supportingDocument->institution }}</td>
                <td>{{ $supportingDocument->partner_name }}</td>
                <td>{{ $supportingDocument->funding_contribution1 }}</td>
            </tr>
        @endforeach
    </table>

    <h2>4. LUARAN DAN TARGET CAPAIAN</h2>
    <p>Luaran Wajib</p>
    <table>
        <tr>
            <th>Tahun Luaran</th>
            <th>Kategori Luaran</th>
            <th>Jenis Luaran</th>
            <th>Status Target Capaian</th>
            <th>Keterangan</th>
        </tr>
        @foreach ($research['output'] as $output)
            <tr>
                <td>{{ $output->year }}</td>
                <td>{{ $output->category->name }}</td>
                <td>{{ $output->type->name }}</td>
                <td>{{ $output->status }}</td>
                <td>{{ $output->description }}</td>
            </tr>
        @endforeach
    </table>

    <h2>5. ANGGARAN</h2>
    <p>Rencana Anggaran Biaya penelitian mengacu pada PMK dan buku Panduan Penelitian dan Pengabdian kepada Masyarakat
        yang berlaku.</p>
    @foreach ($budgetPlan as $plan)
        <p>Total RAB {{ $plan['year'] }} Tahun Rp{{ $plan['total'] }},00</p>
        <table>
            <tr>
                <th>Kelompok</th>
                <th>Komponen</th>
                <th>Item</th>
                <th>Satuan</th>
                <th>Vol.</th>
                <th>Biaya Satuan</th>
                <th>Total</th>
            </tr>
            @foreach ($plan['plans'] as $budgetPlan)
                <tr>
                    <td>{{ $budgetPlan->budgetGroup->name }}</td>
                    <td>{{ $budgetPlan->budgetComponent->name }}</td>
                    <td>{{ $budgetPlan->item }}</td>
                    <td>{{ $budgetPlan->unit }}</td>
                    <td>{{ $budgetPlan->volume }}</td>
                    <td>{{ $budgetPlan->price_unit }}</td>
                    <td>{{ $budgetPlan->total }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach

    @if ($approval)
        @foreach ($approval as $approve)
            <p>Persetujuan Pengusul</p>
            <table>
                <tr>
                    <th>Tanggal Pengiriman</th>
                    <th>Tanggal Persetujuan</th>
                    <th>Nama Pimpinan Pemberi Persetujuan</th>
                    <th>Sebutan Jabatan Unit</th>
                    <th>Nama Unit Lembaga Pengusul</th>
                </tr>
                <tr>
                    <td>{{ $research['created_at'] }}</td>
                    <td>{{ $approve['created_at'] }}</td>
                    <td>{{ $approve['user']->name }}</td>
                    <td>{{ $approve['user']->roles->filter(function ($role) {
                            return $role->name === 'Kaprodi' || $role->name === 'Kepala LPPM';
                        })->first()->name ?? null }}
                    </td>
                    <td>{{ $approve['user']->institution }}</td>
                </tr>
            </table>
            <p>Komentar: {{ $approve['newStatus']->name }}</p>
            <div style="border: 1px solid black;">
                <p> {{ $approve['notes'] }}</p>
            </div>
        @endforeach
    @endif

</body>

</html>
