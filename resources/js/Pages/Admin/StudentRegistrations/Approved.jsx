import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from '@/Components/ui/sheet';
import { Textarea } from '@/Components/ui/textarea';
import { flashMessage } from '@/lib/utils';
import { useForm } from '@inertiajs/react';
import { IconChecklist } from '@tabler/icons-react';
import { toast } from 'sonner';

export default function Approved({ name, status, statuses, action, classrooms, rejected_description }) {
    const { data, setData, errors, processing, put } = useForm({
        status: status ?? 'Menunggu Konfirmasi',
        rejected_description: rejected_description ?? '',
        classroom_id: null,
        _method: 'PUT',
    });

    console.log(data);

    const onHandleSubmit = (e) => {
        e.preventDefault();
        put(action, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (success) => {
                const flash = flashMessage(success);
                if (flash) toast[flash.type](flash.message);
            },
        });
    };

    return (
        <Sheet>
            <SheetTrigger asChild>
                <Button variant="green" size="sm">
                    <IconChecklist className="size-4 text-white" />
                    Update Status
                </Button>
            </SheetTrigger>
            <SheetContent>
                <SheetHeader>
                    <SheetTitle>Setujui Penerimaan Siswa {name}</SheetTitle>
                    <SheetDescription>
                        Periksa kembali data Siswa tersebut sebelum disetujui. Setelah disetujui, status tidak akan bisa
                        diupdate kembali
                    </SheetDescription>
                </SheetHeader>
                <form className="mt-6 space-y-4" onSubmit={onHandleSubmit}>
                    <div className="grid w-full items-center gap-1.5">
                        <Label htmlFor="status">Status</Label>
                        <Select
                            defaultValue={data.status}
                            onValueChange={(value) => {
                                setData('status', value);
                                setData('classroom_id', null);
                                setData('rejected_description', '');
                            }}
                        >
                            <SelectTrigger>
                                <SelectValue>
                                    {statuses.find((status) => status.value == data.status)?.label ?? 'Pilih Status'}
                                </SelectValue>
                            </SelectTrigger>
                            <SelectContent>
                                {statuses.map((status, index) => (
                                    <SelectItem key={index} value={status.value}>
                                        {status.label}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>
                    {data.status == 'Disetujui' && (
                        <div className="grid w-full items-center gap-1.5">
                            <Label htmlFor="classroom">Pilih Kelas</Label>
                            <Select
                                defaultValue={data.classroom_id}
                                onValueChange={(value) => setData('classroom_id', value)}
                            >
                                <SelectTrigger>
                                    <SelectValue>
                                        {classrooms.find((classroom) => classroom.value == data.classroom_id)?.label ??
                                            'Pilih Kelas'}
                                    </SelectValue>
                                </SelectTrigger>
                                <SelectContent>
                                    {classrooms.map((classroom, index) => (
                                        <SelectItem key={index} value={classroom.value}>
                                            {classroom.label}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                    )}
                    {data.status == 'Ditolak' && (
                        <div className="grid w-full items-center gap-1.5">
                            <Label htmlFor="rejected_description">Alasan Ditolak</Label>
                            <Textarea
                                name="rejected_description"
                                maxLength="255"
                                id="rejected_description"
                                onChange={(e) => setData(e.target.name, e.target.value)}
                                placeholder="Masukkan keterangan..."
                                value={data.rejected_description}
                            ></Textarea>
                        </div>
                    )}
                    <Button type="submit" variant="blue" disabled={processing}>
                        Save
                    </Button>
                </form>
            </SheetContent>
        </Sheet>
    );
}
