@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <table style="margin: auto;">
                <tr>
                    <td><x-logo.mark /></td>
                    <td>{{ $slot }}</td>
                </tr>
            </table>
        </a>
    </td>
</tr>
