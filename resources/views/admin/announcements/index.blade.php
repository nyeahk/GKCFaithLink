@foreach($announcements as $announcement)
<tr>
    <td>{{ $announcement->id }}</td>
    <td>{{ $announcement->title }}</td>
    <td>{{ Str::limit($announcement->content, 50) }}</td>
    <td>
        @if($announcement->status == 'published')
            <span class="badge bg-success">Published</span>
        @elseif($announcement->status == 'draft')
            <span class="badge bg-warning text-dark">Draft</span>
        @elseif($announcement->status == 'declined')
            <span class="badge bg-danger">Declined</span>
        @endif
    </td>
    <td>{{ $announcement->created_at->format('M d, Y') }}</td>
    <td>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.announcements.show', $announcement->id) }}" class="btn btn-sm btn-info">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i>
            </a>
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $announcement->id }}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </td>
</tr>
@endforeach