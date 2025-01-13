<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Service Proposal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Proposal Pengabdian Masyarakat</h1>
        <h3>{{ $communityService->title }}</h3>
    </div>

    <div class="content">
        <p><strong>Kategori:</strong> {{ $communityService->category->name }}</p>
        <p><strong>Skema:</strong> {{ $communityService->scheme->name }}</p>
        <p><strong>Ruang Lingkup:</strong> {{ $communityService->scope->name }}</p>
        <p><strong>Year:</strong> {{ $communityService->year }}</p>
        <p><strong>Durasi:</strong> {{ $communityService->duration }} months</p>
        <p><strong>Rumpun Ilmu:</strong></p>
        <ul>
            <li>Level 1: {{ $communityService->cluster_lv1}}</li>
            <li>Level 2: {{ $communityService->cluster_lv2}}</li>
            <li>Level 3: {{ $communityService->cluster_lv3}}</li>
        </ul>
        <p><strong>Leader:</strong> {{ $communityService->leader_name }} ({{ $communityService->leader_task }})</p>
    </div>

    <div class="section-title">Luaran Pemberdayaan Mitra</div>
    <table>
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>jenis</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($communityService->outputPartner as $index => $partner)
                <tr>
                    <td>{{ $partner->category->name }}</td>
                    <td>{{ $partner->category->name }}</td>
                    <td>{{ $partner->type->name }}</td>
                    <td>{{ $partner->status ? 'Completed' : 'Pending' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Luaran Publikasi</div>
    <table>
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>jenis</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($communityService->outputPublication as $index => $publication)
                <tr>
                    <td>{{ $publication->category->name }}</td>
                    <td>{{ $publication->type->name }}</td>
                    <td>{{ $publication->status ? 'Completed' : 'Pending' }}</td>
                    <td>{{ $publication->year }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Luaran Publikasi Media</div>
    <table>
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>jenis</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($communityService->outputMedia as $index => $media)
                <tr>
                    <td>{{ $media->description }}</td>
                    <td>{{ $media->category->name }}</td>
                    <td>{{ $media->type->name }}</td>
                    <td>{{ $media->status ? 'Completed' : 'Pending' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Luaran Video</div>
    <table>
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>jenis</th>
                <th>Status</th>
        </thead>
        <tbody>
            @foreach ($communityService->outputVideo as $index => $video)
                <tr>
                    <td>{{ $video->description }}</td>
                    <td>{{ $video->category->name }}</td>
                    <td>{{ $video->type->name }}</td>
                    <td>{{ $video->status ? 'Completed' : 'Pending' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Rencana Anggaran Belanja</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tahun</th>
                <th>Kelompok</th>
                <th>Komponen</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Volume</th>
                <th>Price/Unit</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($communityService->budgetPlanService as $index => $budget)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $budget->year }}</td>
                    <td>{{ $budget->group }}</td>
                    <td>{{ $budget->component }}</td>
                    <td>{{ $budget->item }}</td>
                    <td>{{ $budget->unit }}</td>
                    <td>{{ $budget->volume }}</td>
                    <td>{{ number_format($budget->price_unit, 0, ',', '.') }}</td>
                    <td>{{ number_format($budget->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Mitra</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Provinsi</th>
                <th>Nama Pimpinan</th>
                <th>Kelompok</th>
                <th>Jenis</th>
                <th>Email</th>
                <th>Kontribusi Dana</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($communityService->partner as $index => $partner)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $partner->name }}</td>
                    <td>{{ $partner->province }}</td>
                    <td>{{ $partner->leader_name }}</td>
                    <td>{{ $partner->group }}</td>
                    <td>{{ $partner->type}}</td>
                    <td>{{ $partner->email }}</td>
                    <td>{{ number_format($partner->funding_contribution, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">File Pendukung</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Dokumen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($communityService->supportingFile as $index => $file)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $file->type}}</td>
                    <td>{{ $file->document ? 'Available' : 'Not Uploaded' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Anggota</div>
    <ul>
        @foreach ($communityService->members as $member)
            <li>{{ $member->name }} ({{ $member->pivot->task }})</li>
        @endforeach
    </ul>
</body>
</html>
