<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur EduManager</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb; padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background:#2563eb; padding:20px;">
                            <h1 style="color:#ffffff; margin:0;">EduManager</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#1f2937;">
                            <p style="font-size:16px; margin-bottom:20px;">
                                Bonjour <strong>{{ $enseignant->prenom ?? $enseignant->nom_famille }}</strong>,
                            </p>

                            <p style="font-size:15px; margin-bottom:20px;">
                                Un compte enseignant vous a été créé par le parent <strong>{{ $user->prenom_nom ?? $user->nom_famille }}</strong> sur <strong>EduManager</strong>.  
                                Grâce à ce compte, vous serez en contact avec lui sur l'évolution des cours de répétitions de ses enfants.
                            </p>

                            <p style="font-size:15px; margin-bottom:20px;">
                                Pour vous connecter, cliquez sur le bouton ci-dessous :
                            </p>

                            <p align="center" style="margin:30px 0;">
                                <a href="http://localhost:3000/" 
                                   style="background:#2563eb; color:#ffffff; padding:12px 24px; text-decoration:none; border-radius:6px; font-weight:bold;">
                                    Se connecter à EduManager
                                </a>
                            </p>

                            <p style="font-size:15px; margin-bottom:20px;">
                                Voici vos identifiants de connexion :
                            </p>

                            <table cellpadding="10" cellspacing="0" width="100%" style="background:#f3f4f6; border-radius:6px; margin-bottom:20px;">
                                <tr>
                                    <td><strong>Email :</strong></td>
                                    <td>{{ $enseignant->courriel }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Mot de passe :</strong></td>
                                    <td><code style="background:#e5e7eb; padding:4px 8px; border-radius:4px;">{{ $password }}</code></td>
                                </tr>
                            </table>

                            <p style="font-size:14px; color:#6b7280;">
                                ⚠️ Pensez à modifier votre mot de passe après votre première connexion.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background:#f3f4f6; padding:20px; color:#6b7280; font-size:13px;">
                            &copy; {{ date('Y') }} EduManager. Tous droits réservés.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
