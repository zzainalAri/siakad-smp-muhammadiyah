import { Button } from '@/Components/ui/button';
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from '@/Components/ui/sheet';
import { Table, TableBody, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import { formatDateIndo, formatToRupiah } from '@/lib/utils';
import { IconEye } from '@tabler/icons-react';
import { Alert, AlertDescription } from './ui/alert';
import { Badge } from './ui/badge';

export default function Fees({
    fees,
    open,
    loading,
    setOpen,
    total_fees,
    paid_fees_sum,
    unpaid_fees_sum,
    name,
    handlePayment = null,
}) {
    const today = new Date().toISOString().split('T')[0];

    const statusVariant = {
        'Sudah Bayar': 'success',
        'Belum Bayar': 'default',
        'Jatuh Tempo': 'red',
    };

    return (
        <Sheet open={open} onOpenChange={setOpen}>
            <SheetTrigger asChild>
                <Button variant="purple" size="sm">
                    <IconEye className="size-4 text-white" />
                    Lihat Detail
                </Button>
            </SheetTrigger>
            <SheetContent side="top" className="scroll-bar max-h-screen overflow-y-scroll">
                <SheetHeader>
                    <SheetTitle>Detail Pembayaran SPP {name}</SheetTitle>
                    <SheetDescription>
                        Detail Pembayaran SPP Siswa dari awal tahun ajaran hingga akhir tahun ajaran Pembayaran baru
                        {handlePayment && (
                            <Alert variant="orange" className="my-3">
                                <AlertDescription>
                                    Pembayaran bisa dilakukan setelah memasuki tanggal penetapan yang telah ditentukan.
                                </AlertDescription>
                            </Alert>
                        )}
                    </SheetDescription>
                </SheetHeader>
                <Table className="w-full border">
                    <TableHeader>
                        <TableRow>
                            <TableHead className="border">No</TableHead>
                            <TableHead className="border">No Pembayaran</TableHead>
                            <TableHead className="border">Jumlah</TableHead>
                            <TableHead className="border">Tanggal Ditetapkan Pembayaran</TableHead>
                            <TableHead className="border">Jatuh Tempo</TableHead>
                            <TableHead className="border">Status</TableHead>
                            {handlePayment && <TableHead className="border">Aksi</TableHead>}
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {fees.map((fee, index) => (
                            <TableRow key={index}>
                                <TableCell className="border">{index + 1}</TableCell>
                                <TableCell className="border">{fee.fee_code}</TableCell>
                                <TableCell className="border">{formatToRupiah(fee.amount)}</TableCell>
                                <TableCell className="border">{formatDateIndo(fee.billing_date)}</TableCell>
                                <TableCell className="border">{formatDateIndo(fee.due_date)}</TableCell>
                                <TableCell className="border">
                                    {fee.status == 'Sudah Bayar' && <Badge variant={'success'}>{fee.status}</Badge>}
                                    {fee.status == 'Belum Bayar' && <Badge variant={'default'}>{fee.status}</Badge>}
                                    {fee.status == 'Jatuh Tempo' && <Badge variant={'red'}>{fee.status}</Badge>}
                                </TableCell>
                                {handlePayment && (
                                    <TableCell className="border">
                                        {fee.billing_date > today || fee.status == 'Sudah Bayar' ? null : (
                                            <Button
                                                size="sm"
                                                disabled={loading}
                                                className={loading ? 'opacity-55 hover:cursor-not-allowed' : ''}
                                                onClick={() => handlePayment({ fee_id: fee.id, amount: fee.amount })}
                                                variant="slate"
                                            >
                                                Bayar
                                            </Button>
                                        )}
                                    </TableCell>
                                )}
                            </TableRow>
                        ))}
                    </TableBody>
                    <TableFooter className="font-bold">
                        <TableRow>
                            <TableCell colSpan="3">Total Sudah Dibayar</TableCell>
                            <TableCell className="border" colSpan={handlePayment ? '4' : '3'}>
                                {formatToRupiah(paid_fees_sum)}
                            </TableCell>
                        </TableRow>
                        <TableRow>
                            <TableCell colSpan="3">Total Belum Dibayar</TableCell>
                            <TableCell className="border" colSpan={handlePayment ? '4' : '3'}>
                                {formatToRupiah(unpaid_fees_sum)}
                            </TableCell>
                        </TableRow>
                        <TableRow>
                            <TableCell colSpan="3">Total Tagihan Keseluruhan</TableCell>
                            <TableCell className="border" colSpan={handlePayment ? '4' : '3'}>
                                {formatToRupiah(total_fees)}
                            </TableCell>
                        </TableRow>
                    </TableFooter>
                </Table>
            </SheetContent>
        </Sheet>
    );
}
