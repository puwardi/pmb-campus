<div class="form-grid">
<label>Nama lengkap<input name="name" required maxlength="100" value="<?= e($data['name'] ?? '') ?>">
</label>
<label>Email<input name="email" type="email" required maxlength="150" value="<?= e($data['email'] ?? '') ?>">
</label>
<label>Nomor telepon<input name="phone" type="tel" required maxlength="25" value="<?= e($data['phone'] ?? '') ?>">
</label>
<label>Asal sekolah<input name="school_origin" required maxlength="150" value="<?= e($data['school_origin'] ?? '') ?>">
</label>
<label class="full">Alamat<textarea name="address" required maxlength="1000" rows="3"><?= e($data['address'] ?? '') ?></textarea>
</label>
<label class="full">Password <?= !empty($editing) ? '(kosongkan jika tidak diubah)' : '(8–72 karakter)' ?><input name="password" type="password" minlength="8" maxlength="72" autocomplete="new-password" <?= empty($editing) ? 'required' : '' ?>>
</label>
</div>